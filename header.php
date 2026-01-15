<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('min-h-screen flex flex-col'); ?>>
    <?php wp_body_open(); ?>

    <a class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:rounded-xl focus:border focus:border-slate-200 focus:bg-white focus:px-3 focus:py-2" href="#content">Skip to content</a>

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white" role="banner">
      <div class="mx-auto max-w-6xl px-4">
        <div class="relative flex h-14 items-center gap-3">
          <a id="site-brand" class="flex min-w-0 items-center gap-3 transition-opacity duration-200" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
            <?php if (has_custom_logo()) : ?>
              <span class="shrink-0"><?php the_custom_logo(); ?></span>
            <?php else : ?>
              <?php $siteIconUrl = get_site_icon_url(64); ?>
              <?php if (is_string($siteIconUrl) && $siteIconUrl !== '') : ?>
                <img class="h-8 w-8 rounded-full" src="<?php echo esc_url($siteIconUrl); ?>" alt="" width="32" height="32" loading="eager" decoding="async">
              <?php endif; ?>
            <?php endif; ?>

            <span class="min-w-0">
              <span class="block truncate text-lg font-extrabold leading-tight tracking-tight text-slate-900 md:text-xl"><?php bloginfo('name'); ?></span>
              <?php $tagline = get_bloginfo('description', 'display'); ?>
              <?php if ($tagline) : ?>
                <span class="block truncate text-xs font-medium leading-tight text-slate-500"><?php echo esc_html($tagline); ?></span>
              <?php endif; ?>
            </span>
          </a>

          <form class="hidden flex-1 items-center md:flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <label class="sr-only" for="site-search">Search</label>
            <div class="flex w-full max-w-xl items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 shadow-sm">
              <input id="site-search" class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search the web">
              <button class="ml-2 inline-flex h-9 w-10 items-center justify-center rounded-full bg-purple-700 text-white" type="submit" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
              </button>
            </div>
          </form>

          <div id="mobile-actions" class="ml-auto flex items-center gap-2 transition-opacity duration-200 md:hidden">
            <button id="mobile-search-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700" type="button" aria-label="Search" aria-controls="mobile-search" aria-expanded="false">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
              </svg>
            </button>

            <button id="mobile-menu-toggle" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700" type="button" aria-label="Menu" aria-controls="mobile-menu" aria-expanded="false">
              <svg data-mobile-menu-icon="open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg data-mobile-menu-icon="close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
              </svg>
            </button>
          </div>

          <div id="mobile-search" class="pointer-events-none absolute inset-x-0 top-0 z-10 flex h-14 items-center bg-white opacity-0 transition-[opacity,transform] duration-200 ease-out [transform-origin:right_center] [transform:scaleX(0.92)] md:hidden">
            <form class="mx-0 flex w-full items-center px-0" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
              <label class="sr-only" for="site-search-mobile">Search</label>
              <div class="mx-auto flex w-full items-center gap-2 px-4">
                <div class="flex w-full items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 shadow-sm">
                  <input id="site-search-mobile" class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search the web">
                  <button class="ml-2 inline-flex h-9 w-10 items-center justify-center rounded-full bg-purple-700 text-white" type="submit" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                  </button>
                </div>

                <button id="mobile-search-close" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700" type="button" aria-label="Close search">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                  </svg>
                </button>
              </div>
            </form>
          </div>

          <nav class="hidden items-center gap-6 text-sm text-slate-700 md:flex" aria-label="Primary">
            <?php
            $navCategories = get_categories([
                'taxonomy' => 'category',
                'orderby' => 'count',
                'order' => 'DESC',
                'hide_empty' => true,
                'number' => 20,
            ]);

            if (is_array($navCategories) && $navCategories !== []) {
                $navItems = [];
                foreach ($navCategories as $cat) {
                    if (!$cat instanceof WP_Term) continue;

                    $link = get_category_link($cat);
                    if (!is_string($link) || $link === '') continue;

                    $navItems[] = [
                        'name' => $cat->name,
                        'url' => $link,
                    ];
                }

                $primaryItems = array_slice($navItems, 0, 4);
                $moreItems = array_slice($navItems, 4);

                echo '<ul class="flex items-center gap-6">';
                foreach ($primaryItems as $item) {
                    echo '<li><a class="rounded-full px-3 py-2 font-semibold text-slate-700 no-underline transition-colors hover:bg-slate-100 hover:text-slate-900 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2" href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a></li>';
                }

                if ($moreItems !== []) {
                    echo '<li class="relative group">';
                    echo '<button class="rounded-full px-3 py-2 font-semibold text-slate-700 no-underline transition-colors hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2" type="button" aria-haspopup="true">More</button>';
                    echo '<div class="absolute left-0 top-full z-[60] hidden w-60 pt-2 group-hover:block group-focus-within:block">';
                    echo '<div class="rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">';
                    echo '<ul class="flex flex-col">';
                    foreach ($moreItems as $item) {
                        echo '<li><a class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 no-underline hover:bg-slate-50 hover:text-slate-900 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2" href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }

                echo '</ul>';
            }
            ?>
          </nav>

        </div>

        <div id="mobile-menu" class="fixed inset-x-0 top-14 bottom-0 z-[70] hidden md:hidden" role="dialog" aria-modal="true" aria-label="Mobile menu">
          <button id="mobile-menu-backdrop" class="absolute inset-0 bg-black/30" type="button" aria-label="Close menu"></button>

          <div class="relative mx-auto max-w-6xl px-4">
            <div class="mt-3 max-h-[70vh] overflow-auto rounded-2xl border border-slate-200 bg-white shadow-xl">
              <div class="px-4 py-4">
                <nav class="text-sm text-slate-700" aria-label="Primary">
                <?php
                $mobileCategories = get_categories([
                    'taxonomy' => 'category',
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'hide_empty' => true,
                    'number' => 20,
                ]);

                if (is_array($mobileCategories) && $mobileCategories !== []) {
                    echo '<ul class="flex flex-col gap-3">';
                    foreach ($mobileCategories as $cat) {
                        if (!$cat instanceof WP_Term) {
                            continue;
                        }

                        $link = get_category_link($cat);
                        if (!is_string($link) || $link === '') {
                            continue;
                        }

                        echo '<li><a class="block rounded-xl border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700 no-underline shadow-sm transition-colors hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 hover:no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-600 focus-visible:ring-offset-2" href="' . esc_url($link) . '">' . esc_html($cat->name) . '</a></li>';
                    }
                    echo '</ul>';
                }
                ?>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <div id="trend-bar" class="flex w-full flex-col gap-2 overflow-hidden border-t border-slate-100 bg-white py-2 text-[11px] text-slate-600 transition-[max-height,opacity] duration-200 ease-out sm:text-xs md:flex-row md:items-center md:gap-4 md:text-sm md:duration-200 md:ease-out">
          <span class="font-semibold text-slate-700">Today's news</span>
          <nav class="min-w-0 flex-1" aria-label="Trending">
            <?php
            $trendTags = get_tags([
                'orderby' => 'count',
                'order' => 'DESC',
                'hide_empty' => true,
                'number' => 12,
            ]);

            if (is_array($trendTags) && $trendTags !== []) {
                echo '<ul class="flex flex-wrap items-center gap-x-4 gap-y-2">';
                foreach ($trendTags as $tag) {
                    if (!$tag instanceof WP_Term) {
                        continue;
                    }
                    $link = get_term_link($tag);
                    if (!is_string($link) || $link === '') {
                        continue;
                    }
                    echo '<li><a href="' . esc_url($link) . '">' . esc_html($tag->name) . '</a></li>';
                }
                echo '</ul>';
            }
            ?>
          </nav>
        </div>
      </div>
    </header>

    <div id="content" class="site-content flex-1">
