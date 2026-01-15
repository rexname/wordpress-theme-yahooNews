<?php

get_header();

?>

<?php if (false) : ?>
  <main class="mx-auto max-w-6xl px-4 py-6">
    <header class="mb-6">
      <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl"><?php single_cat_title(); ?></h1>
    </header>

    <?php if (have_posts()) : ?>
      <?php
      $term = get_queried_object();
      $termName = $term instanceof WP_Term ? $term->name : single_cat_title('', false);
      $siteName = (string) get_bloginfo('name');

      $top = [];
      $idx = 0;
      while (have_posts() && $idx < 4) {
          the_post();
          $id = get_the_ID();
          $top[] = [
              'id' => $id,
              'title' => get_the_title(),
              'link' => get_permalink(),
              'time' => human_time_diff((int) get_the_time('U'), (int) current_time('timestamp')) . ' ago',
              'img0' => has_post_thumbnail($id)
                  ? get_the_post_thumbnail($id, 'large', ['class' => 'h-full w-full object-cover'])
                  : '<div class="h-full w-full bg-slate-100"></div>',
              'img1' => has_post_thumbnail($id)
                  ? get_the_post_thumbnail($id, 'large', ['class' => 'h-full w-full object-cover'])
                  : '<div class="h-full w-full bg-slate-100"></div>',
              'img2' => has_post_thumbnail($id)
                  ? get_the_post_thumbnail($id, 'medium_large', ['class' => 'h-full w-full object-cover'])
                  : '<div class="h-full w-full bg-slate-100"></div>',
          ];
          $idx += 1;
      }
      rewind_posts();
      ?>

      <div class="hidden md:grid md:grid-cols-12 md:gap-6">
        <?php if (isset($top[0])) : ?>
          <article class="md:col-span-8">
            <a class="group relative block h-[420px] overflow-hidden rounded-2xl bg-slate-100" href="<?php echo esc_url($top[0]['link']); ?>">
              <?php echo $top[0]['img0']; ?>
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
              <div class="absolute inset-x-0 bottom-0 p-6">
                <div class="flex items-center gap-2 text-xs font-semibold text-white/90">
                  <span class="rounded-full bg-white/15 px-2 py-1"><?php echo esc_html($termName); ?></span>
                  <span class="text-white/70">•</span>
                  <span class="text-white/80"><?php echo esc_html($siteName); ?></span>
                  <span class="text-white/70">•</span>
                  <span class="text-white/80"><?php echo esc_html($top[0]['time']); ?></span>
                </div>
                <h2 class="mt-3 text-3xl font-extrabold leading-tight text-white">
                  <?php echo esc_html($top[0]['title']); ?>
                </h2>
              </div>
            </a>
          </article>
        <?php endif; ?>

        <div class="md:col-span-4 md:grid md:gap-6">
          <?php if (isset($top[1])) : ?>
            <article>
              <a class="group relative block h-[210px] overflow-hidden rounded-2xl bg-slate-100" href="<?php echo esc_url($top[1]['link']); ?>">
                <?php echo $top[1]['img1']; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-4">
                  <div class="flex items-center gap-2 text-[11px] font-semibold text-white/90">
                    <span class="rounded-full bg-white/15 px-2 py-1"><?php echo esc_html($termName); ?></span>
                    <span class="text-white/70">•</span>
                    <span class="text-white/80"><?php echo esc_html($top[1]['time']); ?></span>
                  </div>
                  <h3 class="mt-2 line-clamp-3 text-lg font-extrabold leading-snug text-white">
                    <?php echo esc_html($top[1]['title']); ?>
                  </h3>
                </div>
              </a>
            </article>
          <?php endif; ?>

          <div class="grid grid-cols-2 gap-6">
            <?php if (isset($top[2])) : ?>
              <article>
                <a class="group block overflow-hidden rounded-2xl border border-slate-200 bg-white no-underline hover:no-underline" href="<?php echo esc_url($top[2]['link']); ?>">
                  <div class="h-24 bg-slate-100">
                    <?php echo $top[2]['img2']; ?>
                  </div>
                  <div class="p-3">
                    <h3 class="line-clamp-3 text-sm font-extrabold leading-snug text-slate-900 group-hover:text-purple-700">
                      <?php echo esc_html($top[2]['title']); ?>
                    </h3>
                    <div class="mt-2 text-[11px] font-semibold text-slate-500"><?php echo esc_html($top[2]['time']); ?></div>
                  </div>
                </a>
              </article>
            <?php endif; ?>

            <?php if (isset($top[3])) : ?>
              <article>
                <a class="group block overflow-hidden rounded-2xl border border-slate-200 bg-white no-underline hover:no-underline" href="<?php echo esc_url($top[3]['link']); ?>">
                  <div class="h-24 bg-slate-100">
                    <?php echo $top[3]['img2']; ?>
                  </div>
                  <div class="p-3">
                    <h3 class="line-clamp-3 text-sm font-extrabold leading-snug text-slate-900 group-hover:text-purple-700">
                      <?php echo esc_html($top[3]['title']); ?>
                    </h3>
                    <div class="mt-2 text-[11px] font-semibold text-slate-500"><?php echo esc_html($top[3]['time']); ?></div>
                  </div>
                </a>
              </article>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="mt-6 space-y-5 md:hidden">
        <?php while (have_posts()) : ?>
          <?php the_post(); ?>
          <?php $cat = yahooNews_post_primary_category(); ?>

          <article <?php post_class('group grid gap-4 md:grid-cols-[176px_1fr]'); ?> data-story-ts="<?php echo esc_attr((string) get_the_time('U')); ?>">
            <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', ['class' => 'h-56 w-full object-cover sm:h-60']); ?>
              <?php else : ?>
                <div class="h-56 w-full bg-slate-100 sm:h-60"></div>
              <?php endif; ?>
            </a>

            <div class="min-w-0">
              <div class="text-xs text-slate-500">
                <?php if ($cat) : ?>
                  <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                  <span class="px-2 text-slate-300">|</span>
                <?php endif; ?>
                <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
              </div>

              <h3 class="mt-1 line-clamp-2 text-base font-extrabold leading-snug text-slate-900">
                <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>

              <p class="mt-1 line-clamp-2 text-sm text-slate-600"><?php echo esc_html(get_the_excerpt()); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <?php rewind_posts(); ?>

      <div class="mt-10 hidden md:block">
        <div class="space-y-6">
          <?php $i = 0; ?>
          <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <?php if ($i < 4) : ?>
              <?php $i += 1; ?>
              <?php continue; ?>
            <?php endif; ?>

            <article <?php post_class('group grid gap-4 md:grid-cols-[280px_1fr]'); ?> data-story-ts="<?php echo esc_attr((string) get_the_time('U')); ?>">
              <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium_large', ['class' => 'h-40 w-full object-cover']); ?>
                <?php else : ?>
                  <div class="h-40 w-full bg-slate-100"></div>
                <?php endif; ?>
              </a>

              <div class="min-w-0">
                <div class="text-xs text-slate-500">
                  <span class="font-semibold text-purple-700"><?php echo esc_html($termName); ?></span>
                  <span class="px-2 text-slate-300">|</span>
                  <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                </div>

                <h3 class="mt-2 line-clamp-2 text-xl font-extrabold leading-snug text-slate-900">
                  <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

                <p class="mt-2 line-clamp-2 text-sm text-slate-600"><?php echo esc_html(get_the_excerpt()); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      </div>
    <?php else : ?>
      <p class="text-sm text-slate-600">No posts found.</p>
    <?php endif; ?>
  </main>
<?php else : ?>
<main class="mx-auto max-w-6xl px-4 py-6">
  <div class="mt-6 grid gap-6 lg:grid-cols-12">
    <section class="lg:col-span-9">
      <?php
      $featuredQuery = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 1,
          'ignore_sticky_posts' => true,
      ]);
      ?>

      <?php if ($featuredQuery->have_posts()) : ?>
        <?php $featuredQuery->the_post(); ?>
        <article <?php post_class('overflow-hidden rounded-xl bg-white'); ?>>
          <a class="block lg:flex" href="<?php the_permalink(); ?>">
            <div class="lg:w-2/3">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['class' => 'js-featured-image h-72 w-full object-cover sm:h-80 lg:h-full']); ?>
              <?php else : ?>
                <div class="h-72 w-full bg-slate-100 sm:h-80 lg:h-full"></div>
              <?php endif; ?>
            </div>

            <div class="js-featured-panel flex flex-col gap-3 p-5 lg:w-1/3">
              <h2 class="text-xl font-extrabold leading-snug text-slate-900"><?php the_title(); ?></h2>
              <p class="text-sm leading-relaxed text-slate-700"><?php echo esc_html(get_the_excerpt()); ?></p>
              <span class="text-sm font-semibold text-slate-900">Read More &raquo;</span>
            </div>
          </a>
        </article>
      <?php else : ?>
        <p class="text-sm text-slate-600">No posts found.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

      <div class="mt-6 lg:hidden">
        <?php
        $latestMobile = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 5,
            'ignore_sticky_posts' => true,
        ]);
        ?>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
          <div class="flex items-center gap-2">
            <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
            <h2 class="text-sm font-extrabold text-slate-900">Latest</h2>
          </div>

          <?php if ($latestMobile->have_posts()) : ?>
            <div class="mt-4 space-y-4">
              <?php while ($latestMobile->have_posts()) : ?>
                <?php $latestMobile->the_post(); ?>
                <?php $cat = yahooNews_post_primary_category(); ?>

                <article <?php post_class('group grid grid-cols-[56px_1fr] gap-3'); ?>>
                  <a class="block overflow-hidden rounded-lg bg-slate-100" href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('thumbnail', ['class' => 'h-14 w-14 object-cover']); ?>
                    <?php else : ?>
                      <div class="h-14 w-14 bg-slate-100"></div>
                    <?php endif; ?>
                  </a>

                  <div class="min-w-0">
                    <div class="text-[11px] text-slate-500">
                      <?php if ($cat) : ?>
                        <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                        <span class="px-2 text-slate-300">|</span>
                      <?php endif; ?>
                      <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                    </div>

                    <h3 class="mt-1 line-clamp-2 text-sm font-extrabold leading-snug text-slate-900">
                      <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                  </div>
                </article>
              <?php endwhile; ?>
            </div>
          <?php else : ?>
            <p class="mt-4 text-sm text-slate-600">No posts found.</p>
          <?php endif; ?>
        </div>

        <?php wp_reset_postdata(); ?>
      </div>

      <?php
      $thumbsQuery = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 5,
          'offset' => 1,
          'ignore_sticky_posts' => true,
      ]);
      ?>

      <?php if ($thumbsQuery->have_posts()) : ?>
        <div class="mt-6">
          <div class="mb-4 flex items-center gap-2">
            <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
            <h2 class="text-sm font-extrabold text-slate-900">Stories for you</h2>
          </div>

          <div id="stories-list" class="space-y-5">
            <?php while ($thumbsQuery->have_posts()) : ?>
              <?php $thumbsQuery->the_post(); ?>
              <?php $cat = yahooNews_post_primary_category(); ?>
              <?php $minutes = yahooNews_post_read_time_minutes(); ?>

              <article <?php post_class('group grid gap-4 md:grid-cols-[176px_1fr]'); ?> data-story-ts="<?php echo esc_attr((string) get_the_time('U')); ?>">
                <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium_large', ['class' => 'h-56 w-full object-cover sm:h-60 md:h-24']); ?>
                  <?php else : ?>
                    <div class="h-56 w-full bg-slate-100 sm:h-60 md:h-24"></div>
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
            <?php endwhile; ?>
          </div>

          <div id="stories-loading" class="mt-4 hidden text-center text-xs font-semibold text-slate-500">Loading...</div>
          <div id="stories-sentinel" class="h-6"></div>
        </div>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

    </section>

    <aside class="hidden lg:col-span-3 lg:block">
    <div class="lg:sticky lg:top-24 lg:h-fit">
      <?php
      $latestAside = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 5,
          'ignore_sticky_posts' => true,
      ]);
      ?>

        <div class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex items-center gap-2">
          <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
          <h2 class="text-sm font-extrabold text-slate-900">Latest</h2>
        </div>

        <?php if ($latestAside->have_posts()) : ?>
          <div class="mt-4 space-y-4">
            <?php while ($latestAside->have_posts()) : ?>
              <?php $latestAside->the_post(); ?>
              <?php $cat = yahooNews_post_primary_category(); ?>

              <article <?php post_class('group grid grid-cols-[56px_1fr] gap-3'); ?>>
                <a class="block overflow-hidden rounded-lg bg-slate-100" href="<?php the_permalink(); ?>">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('thumbnail', ['class' => 'h-14 w-14 object-cover']); ?>
                  <?php else : ?>
                    <div class="h-14 w-14 bg-slate-100"></div>
                  <?php endif; ?>
                </a>

                <div class="min-w-0">
                  <div class="text-[11px] text-slate-500">
                    <?php if ($cat) : ?>
                      <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                      <span class="px-2 text-slate-300">|</span>
                    <?php endif; ?>
                    <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                  </div>

                  <h3 class="mt-1 line-clamp-2 text-sm font-extrabold leading-snug text-slate-900">
                    <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h3>
                </div>
              </article>
            <?php endwhile; ?>
          </div>
        <?php else : ?>
          <p class="mt-4 text-sm text-slate-600">No posts found.</p>
        <?php endif; ?>
        </div>

        <div class="mt-4">
          <?php yahooNews_render_footer_links('', '', ''); ?>
        </div>

        <?php wp_reset_postdata(); ?>
      </div>
    </aside>
  </div>
  </div>
</main>
<?php endif; ?>

<?php get_footer();
