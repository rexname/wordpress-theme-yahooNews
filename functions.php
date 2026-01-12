<?php

declare(strict_types=1);

function yahooNews_vite_dev_server_url(): string
{
    if (defined('YAHOONEWS_VITE_DEV_SERVER') && is_string(YAHOONEWS_VITE_DEV_SERVER) && YAHOONEWS_VITE_DEV_SERVER !== '') {
        return rtrim(YAHOONEWS_VITE_DEV_SERVER, '/');
    }

    $envUrl = getenv('VITE_DEV_SERVER');
    if (is_string($envUrl) && $envUrl !== '') {
        return rtrim($envUrl, '/');
    }

    return 'http://localhost:5173';
}

function yahooNews_vite_dev_server_is_reachable(): bool
{
    $url = yahooNews_vite_dev_server_url() . '/@vite/client';

    if (!function_exists('wp_remote_get')) {
        return false;
    }

    $response = wp_remote_get($url, [
        'timeout' => 2,
        'redirection' => 0,
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    return $code >= 200 && $code < 300;
}

function yahooNews_vite_should_use_dev_server(): bool
{
    if (defined('YAHOONEWS_VITE_DEV')) {
        return (bool) YAHOONEWS_VITE_DEV;
    }

    if (function_exists('wp_get_environment_type')) {
        $env = wp_get_environment_type();
        if (in_array($env, ['local', 'development'], true)) {
            return true;
        }
    }

    return defined('WP_DEBUG') && WP_DEBUG;
}

function yahooNews_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Primary Menu', 'yahoonews'),
        'trending' => __('Trending Menu', 'yahoonews'),
    ]);
}

add_action('after_setup_theme', 'yahooNews_theme_setup');

function yahooNews_post_primary_category(int $postId = 0): ?WP_Term
{
    $postId = $postId > 0 ? $postId : get_the_ID();
    if (!$postId) {
        return null;
    }

    $terms = get_the_terms($postId, 'category');
    if (!is_array($terms) || $terms === []) {
        return null;
    }

    $first = $terms[0];
    return $first instanceof WP_Term ? $first : null;
}

function yahooNews_post_read_time_minutes(int $postId = 0): int
{
    $postId = $postId > 0 ? $postId : get_the_ID();
    if (!$postId) {
        return 1;
    }

    $content = get_post_field('post_content', $postId);
    if (!is_string($content) || $content === '') {
        return 1;
    }

    $text = wp_strip_all_tags($content);
    $words = str_word_count($text);
    $minutes = (int) ceil($words / 200);
    return max(1, $minutes);
}

function yahooNews_vite_enqueue_assets(): void
{
    $devServer = yahooNews_vite_dev_server_url();
    $entry = 'src/main.js';

    $shouldUseDevServer = yahooNews_vite_should_use_dev_server();
    $isForced = defined('YAHOONEWS_VITE_DEV');

    if ($shouldUseDevServer && ($isForced || yahooNews_vite_dev_server_is_reachable())) {
        wp_enqueue_script('yahoonews-vite-client', $devServer . '/@vite/client', [], null, true);

        wp_enqueue_script('yahoonews-vite-main', $devServer . '/' . $entry, [], null, true);

        return;
    }

    $manifestPath = get_stylesheet_directory() . '/dist/.vite/manifest.json';
    if (!is_readable($manifestPath)) {
        return;
    }

    $manifestRaw = file_get_contents($manifestPath);
    if ($manifestRaw === false) {
        return;
    }

    $manifest = json_decode($manifestRaw, true);
    if (!is_array($manifest) || !isset($manifest[$entry]) || !is_array($manifest[$entry])) {
        return;
    }

    $entryData = $manifest[$entry];
    $distUri = get_stylesheet_directory_uri() . '/dist/';

    if (isset($entryData['css']) && is_array($entryData['css'])) {
        foreach ($entryData['css'] as $index => $cssFile) {
            if (!is_string($cssFile) || $cssFile === '') {
                continue;
            }

            wp_enqueue_style('yahoonews-vite-css-' . (string) $index, $distUri . $cssFile, [], null);
        }
    }

    if (isset($entryData['file']) && is_string($entryData['file']) && $entryData['file'] !== '') {
        wp_enqueue_script('yahoonews-vite-js', $distUri . $entryData['file'], [], null, true);
    }
}

add_action('wp_enqueue_scripts', 'yahooNews_vite_enqueue_assets', 20);

function yahooNews_force_module_scripts(string $tag, string $handle, string $src): string
{
    $moduleHandles = [
        'yahoonews-vite-client',
        'yahoonews-vite-main',
        'yahoonews-vite-js',
    ];

    if (!in_array($handle, $moduleHandles, true)) {
        return $tag;
    }

    if (str_contains($tag, ' type="module"') || str_contains($tag, " type='module'")) {
        return $tag;
    }

    return str_replace('<script ', '<script type="module" ', $tag);
}

add_filter('script_loader_tag', 'yahooNews_force_module_scripts', 10, 3);

function yahooNews_rest_stories(WP_REST_Request $request): WP_REST_Response
{
    $offsetRaw = $request->get_param('offset');
    $offset = is_numeric($offsetRaw) ? (int) $offsetRaw : 0;
    $offset = max(0, $offset);

    $limitRaw = $request->get_param('limit');
    $limit = is_numeric($limitRaw) ? (int) $limitRaw : 5;
    $limit = max(1, min(10, $limit));

    $q = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'offset' => $offset,
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    $items = [];

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();

            $cat = yahooNews_post_primary_category();
            $minutes = yahooNews_post_read_time_minutes();
            $ts = (int) get_the_time('U');

            ob_start();
            ?>
            <article <?php post_class('group grid gap-4 md:grid-cols-[176px_1fr]'); ?> data-story-ts="<?php echo esc_attr((string) $ts); ?>">
              <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium_large', ['class' => 'h-28 w-full object-cover md:h-24']); ?>
                <?php else : ?>
                  <div class="h-28 w-full bg-slate-100 md:h-24"></div>
                <?php endif; ?>
              </a>

              <div class="min-w-0">
                <div class="text-xs text-slate-500">
                  <?php if ($cat) : ?>
                    <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                    <span class="px-2 text-slate-300">|</span>
                  <?php endif; ?>
                  <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                  <span class="px-2 text-slate-300">|</span>
                  <span><?php echo esc_html((string) $minutes); ?> min read</span>
                </div>

                <h3 class="mt-1 line-clamp-2 text-base font-extrabold leading-snug text-slate-900">
                  <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

                <p class="mt-1 line-clamp-2 text-sm text-slate-600"><?php echo esc_html(get_the_excerpt()); ?></p>
              </div>
            </article>
            <?php
            $html = (string) ob_get_clean();

            $items[] = [
                'html' => $html,
                'ts' => $ts,
            ];
        }
    }

    wp_reset_postdata();

    return new WP_REST_Response([
        'items' => $items,
        'nextOffset' => $offset + count($items),
        'hasMore' => ($offset + count($items)) < (int) $q->found_posts,
    ], 200);
}

function yahooNews_register_rest_routes(): void
{
    register_rest_route('yahoonews/v1', '/stories', [
        'methods' => 'GET',
        'callback' => 'yahooNews_rest_stories',
        'permission_callback' => '__return_true',
        'args' => [
            'offset' => [
                'required' => false,
            ],
            'limit' => [
                'required' => false,
            ],
        ],
    ]);
}

add_action('rest_api_init', 'yahooNews_register_rest_routes');
