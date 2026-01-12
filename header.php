<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <a class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:rounded-xl focus:border focus:border-slate-200 focus:bg-white focus:px-3 focus:py-2" href="#content">Skip to content</a>

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white" role="banner">
      <div class="mx-auto max-w-6xl px-4">
        <div class="flex h-14 items-center gap-4">
          <a class="flex min-w-0 flex-col leading-tight" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
            <span class="truncate text-lg font-extrabold tracking-tight text-slate-900 md:text-xl"><?php bloginfo('name'); ?></span>
            <?php $tagline = get_bloginfo('description', 'display'); ?>
            <?php if ($tagline) : ?>
              <span class="truncate text-xs font-medium text-slate-500"><?php echo esc_html($tagline); ?></span>
            <?php endif; ?>
          </a>

          <form class="flex flex-1 items-center" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <label class="sr-only" for="site-search">Search</label>
            <div class="flex w-full max-w-xl items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 shadow-sm">
              <input id="site-search" class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search the web">
              <button class="ml-2 inline-flex h-9 w-9 items-center justify-center rounded-full bg-purple-700 text-white" type="submit" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.8-5.2a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
              </button>
            </div>
          </form>

          <nav class="hidden items-center gap-6 text-sm text-slate-700 md:flex" aria-label="Primary">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'fallback_cb' => false,
                    'depth' => 1,
                    'menu_class' => 'flex items-center gap-6',
                ]);
            } else {
                echo '<ul class="flex items-center gap-6"><li><a href="#">News</a></li><li><a href="#">Finance</a></li><li><a href="#">Sports</a></li><li><a href="#">More</a></li></ul>';
            }
            ?>
          </nav>

          <div class="hidden items-center gap-3 md:flex">
            <a class="rounded-full border border-slate-200 px-4 py-2 text-sm text-slate-700" href="#">Sign in</a>
          </div>
        </div>

        <div id="trend-bar" class="flex h-10 items-center gap-4 overflow-hidden border-t border-slate-100 text-xs text-slate-600 transition-[height,opacity] duration-200">
          <span class="font-semibold text-slate-700">Today's news</span>
          <nav class="min-w-0 flex-1" aria-label="Trending">
            <?php
            if (has_nav_menu('trending')) {
                wp_nav_menu([
                    'theme_location' => 'trending',
                    'container' => false,
                    'fallback_cb' => false,
                    'depth' => 1,
                    'menu_class' => 'flex flex-wrap items-center gap-x-4 gap-y-2',
                ]);
            } else {
                echo '<ul class="flex flex-wrap items-center gap-x-4 gap-y-2"><li><a href="#">US</a></li><li><a href="#">Politics</a></li><li><a href="#">World</a></li><li><a href="#">COVID-19</a></li><li><a href="#">Climate Change</a></li><li><a href="#">Health</a></li><li><a href="#">Science</a></li><li><a href="#">Yahoo Originals</a></li><li><a href="#">Contact Us</a></li></ul>';
            }
            ?>
          </nav>
        </div>
      </div>
    </header>

    <div id="content" class="site-content">
