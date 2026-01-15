<?php

get_header();

?>

<div class="bg-slate-50">
<main class="mx-auto max-w-6xl px-4 py-6">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
      <?php the_post(); ?>
      <?php $cat = yahooNews_post_primary_category(); ?>
      <?php $minutes = yahooNews_post_read_time_minutes(); ?>

      <?php
      $topStories = get_posts([
          'post_type' => 'post',
          'posts_per_page' => 8,
          'ignore_sticky_posts' => true,
          'orderby' => 'date',
          'order' => 'DESC',
          'post__not_in' => [get_the_ID()],
      ]);

      $currentId = get_the_ID();
      $categoryId = $cat instanceof WP_Term ? (int) $cat->term_id : 0;

      $sticky = get_option('sticky_posts');
      $stickyIds = is_array($sticky) ? array_values(array_filter($sticky, 'is_numeric')) : [];
      $featuredQuery = new WP_Query([
          'post_type' => 'post',
          'posts_per_page' => 1,
          'post__not_in' => [$currentId],
          'ignore_sticky_posts' => false,
          'post__in' => $stickyIds,
          'orderby' => 'date',
          'order' => 'DESC',
      ]);
      if (!$featuredQuery->have_posts()) {
          $featuredQuery = new WP_Query([
              'post_type' => 'post',
              'posts_per_page' => 1,
              'post__not_in' => [$currentId],
              'ignore_sticky_posts' => true,
              'orderby' => 'date',
              'order' => 'DESC',
          ]);
      }

      $recommendQueryArgs = [
          'post_type' => 'post',
          'posts_per_page' => 6,
          'post__not_in' => [$currentId],
          'ignore_sticky_posts' => true,
          'orderby' => 'date',
          'order' => 'DESC',
      ];
      if ($categoryId > 0) {
          $recommendQueryArgs['category__in'] = [$categoryId];
      }
      $recommendQuery = new WP_Query($recommendQueryArgs);

      $belowRecommendQueryArgs = [
          'post_type' => 'post',
          'posts_per_page' => 8,
          'post__not_in' => [$currentId],
          'ignore_sticky_posts' => true,
          'orderby' => 'date',
          'order' => 'DESC',
      ];
      if ($categoryId > 0) {
          $belowRecommendQueryArgs['category__in'] = [$categoryId];
      }
      $belowRecommendQuery = new WP_Query($belowRecommendQueryArgs);
      ?>

      <div class="relative rounded-3xl border border-slate-200 bg-white shadow-xl">
        <?php if (is_array($topStories) && $topStories !== []) : ?>
          <div class="px-4 pt-5 sm:px-8">
            <div class="flex items-center gap-2 overflow-hidden rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm">
              <span class="shrink-0 text-xs font-semibold text-slate-700">Top Stories:</span>
              <button id="top-stories-prev" class="hidden shrink-0 text-slate-400 hover:text-slate-600" type="button" aria-label="Geser Top Stories ke kiri">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" /></svg>
              </button>
              <div id="top-stories-scroll" class="min-w-0 flex-1 overflow-x-auto whitespace-nowrap text-xs text-slate-600 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <?php
                $first = true;
                foreach ($topStories as $p) {
                    if (!$p instanceof WP_Post) {
                        continue;
                    }
                    $link = get_permalink($p);
                    $title = get_the_title($p);
                    if (!$first) {
                        echo '<span class="px-2 text-slate-300">|</span>';
                    }
                    $first = false;
                    echo '<a class="hover:text-slate-900" href="' . esc_url($link) . '">' . esc_html($title) . '</a>';
                }
                ?>
              </div>
              <button id="top-stories-next" class="shrink-0 text-slate-400 hover:text-slate-600" type="button" aria-label="Geser Top Stories">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" /></svg>
              </button>
            </div>
          </div>
        <?php endif; ?>

        <div class="grid gap-6 px-4 pb-8 pt-6 lg:grid-cols-12 sm:px-8">
          <section class="lg:col-span-9">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-semibold text-slate-600">
              <?php if ($cat) : ?>
                <a class="text-purple-700 no-underline hover:text-purple-800 hover:no-underline" href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a>
                <span class="text-slate-300">|</span>
              <?php endif; ?>
              <span><?php echo esc_html(get_the_author()); ?></span>
              <span class="text-slate-300">|</span>
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('D, F j, Y \a\t g:i A')); ?></time>
              <span class="text-slate-300">|</span>
              <span><?php echo esc_html((string) $minutes); ?> min read</span>
            </div>

            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl">
              <?php the_title(); ?>
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-3">
              <a class="inline-flex h-9 items-center gap-2 rounded-full border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 no-underline hover:bg-slate-50 hover:no-underline" href="https://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo rawurlencode(get_permalink()); ?>&title=<?php echo rawurlencode(get_the_title()); ?>" rel="noreferrer" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                <span>Add on Google</span>
              </a>

              <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 no-underline hover:bg-slate-50 hover:no-underline" href="#" aria-label="Share">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 7l4-4 4 4" /></svg>
              </a>

            </div>

            <div class="mt-6 overflow-hidden rounded-2xl bg-slate-100">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', ['class' => 'h-[260px] w-full object-cover sm:h-[420px] lg:h-[520px]']); ?>
              <?php else : ?>
                <div class="h-[260px] w-full bg-slate-100 sm:h-[420px] lg:h-[520px]"></div>
              <?php endif; ?>
            </div>

            <div class="article-content mt-8">
              <?php the_content(); ?>
            </div>

            <div class="mt-10 lg:hidden">
              <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                  <div class="flex items-center gap-2">
                    <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
                    <h2 class="text-sm font-bold text-slate-800">Featured</h2>
                  </div>

                  <?php if ($featuredQuery->have_posts()) : ?>
                    <div class="mt-4">
                      <?php while ($featuredQuery->have_posts()) : ?>
                        <?php $featuredQuery->the_post(); ?>
                        <?php $featuredCat = yahooNews_post_primary_category(); ?>
                        <article <?php post_class('group grid grid-cols-[72px_1fr] gap-3'); ?>>
                          <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                              <?php the_post_thumbnail('thumbnail', ['class' => 'h-[72px] w-[72px] object-cover']); ?>
                            <?php else : ?>
                              <div class="h-[72px] w-[72px] bg-slate-100"></div>
                            <?php endif; ?>
                          </a>

                          <div class="min-w-0">
                            <div class="text-[11px] text-slate-500">
                              <?php if ($featuredCat) : ?>
                                <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($featuredCat)); ?>"><?php echo esc_html($featuredCat->name); ?></a>
                                <span class="px-2 text-slate-300">|</span>
                              <?php endif; ?>
                              <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                            </div>

                            <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-slate-800">
                              <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                          </div>
                        </article>
                      <?php endwhile; ?>
                      <?php wp_reset_postdata(); ?>
                    </div>
                  <?php else : ?>
                    <p class="mt-4 text-sm text-slate-600">No posts found.</p>
                  <?php endif; ?>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                  <div class="flex items-center gap-2">
                    <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
                    <h2 class="text-sm font-bold text-slate-800">Recommendations</h2>
                  </div>

                  <?php if ($recommendQuery->have_posts()) : ?>
                    <div class="mt-4 space-y-4">
                      <?php while ($recommendQuery->have_posts()) : ?>
                        <?php $recommendQuery->the_post(); ?>
                        <?php $recommendCat = yahooNews_post_primary_category(); ?>
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
                              <?php if ($recommendCat) : ?>
                                <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($recommendCat)); ?>"><?php echo esc_html($recommendCat->name); ?></a>
                                <span class="px-2 text-slate-300">|</span>
                              <?php endif; ?>
                              <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                            </div>

                            <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-slate-800">
                              <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                          </div>
                        </article>
                      <?php endwhile; ?>
                      <?php wp_reset_postdata(); ?>
                    </div>
                  <?php else : ?>
                    <p class="mt-4 text-sm text-slate-600">No posts found.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </section>

          <aside class="hidden lg:col-span-3 lg:block">
            <?php $featuredQuery->rewind_posts(); ?>
            <?php $recommendQuery->rewind_posts(); ?>
            <div class="lg:sticky lg:top-24 lg:h-fit">
              <div class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                  <div class="flex items-center gap-2">
                    <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
                    <h2 class="text-sm font-bold text-slate-800">Featured</h2>
                  </div>

                  <?php if ($featuredQuery->have_posts()) : ?>
                    <div class="mt-4">
                      <?php while ($featuredQuery->have_posts()) : ?>
                        <?php $featuredQuery->the_post(); ?>
                        <?php $featuredCat = yahooNews_post_primary_category(); ?>
                        <article <?php post_class('group grid grid-cols-[72px_1fr] gap-3'); ?>>
                          <a class="block overflow-hidden rounded-xl bg-slate-100" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                              <?php the_post_thumbnail('thumbnail', ['class' => 'h-[72px] w-[72px] object-cover']); ?>
                            <?php else : ?>
                              <div class="h-[72px] w-[72px] bg-slate-100"></div>
                            <?php endif; ?>
                          </a>

                          <div class="min-w-0">
                            <div class="text-[11px] text-slate-500">
                              <?php if ($featuredCat) : ?>
                                <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($featuredCat)); ?>"><?php echo esc_html($featuredCat->name); ?></a>
                                <span class="px-2 text-slate-300">|</span>
                              <?php endif; ?>
                              <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                            </div>

                            <h3 class="mt-1 line-clamp-3 text-sm font-semibold leading-snug text-slate-800">
                              <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                          </div>
                        </article>
                      <?php endwhile; ?>
                      <?php wp_reset_postdata(); ?>
                    </div>
                  <?php else : ?>
                    <p class="mt-4 text-sm text-slate-600">No posts found.</p>
                  <?php endif; ?>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                  <div class="flex items-center gap-2">
                    <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
                    <h2 class="text-sm font-bold text-slate-800">Recommendations</h2>
                  </div>

                  <?php if ($recommendQuery->have_posts()) : ?>
                    <div class="mt-4 space-y-4">
                      <?php while ($recommendQuery->have_posts()) : ?>
                        <?php $recommendQuery->the_post(); ?>
                        <?php $recommendCat = yahooNews_post_primary_category(); ?>
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
                              <?php if ($recommendCat) : ?>
                                <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($recommendCat)); ?>"><?php echo esc_html($recommendCat->name); ?></a>
                                <span class="px-2 text-slate-300">|</span>
                              <?php endif; ?>
                              <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                            </div>

                            <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-slate-800">
                              <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                          </div>
                        </article>
                      <?php endwhile; ?>
                      <?php wp_reset_postdata(); ?>
                    </div>
                  <?php else : ?>
                    <p class="mt-4 text-sm text-slate-600">No posts found.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </aside>
        </div>
      </div>

      <?php if ($belowRecommendQuery->have_posts()) : ?>
        <section class="mt-6">
          <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex items-center gap-2">
              <span class="h-4 w-1 rounded-sm bg-purple-700"></span>
              <h2 class="text-base font-extrabold text-slate-900">
                <?php if ($cat instanceof WP_Term) : ?>
                  <?php echo esc_html('More from ' . $cat->name); ?>
                <?php else : ?>
                  More stories
                <?php endif; ?>
              </h2>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              <?php while ($belowRecommendQuery->have_posts()) : ?>
                <?php $belowRecommendQuery->the_post(); ?>
                <?php $belowCat = yahooNews_post_primary_category(); ?>
                <article <?php post_class('group overflow-hidden rounded-2xl border border-slate-200 bg-white'); ?>>
                  <a class="block bg-slate-100" href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('medium', ['class' => 'h-36 w-full object-cover']); ?>
                    <?php else : ?>
                      <div class="h-36 w-full bg-slate-100"></div>
                    <?php endif; ?>
                  </a>

                  <div class="p-3">
                    <div class="text-[11px] text-slate-500">
                      <?php if ($belowCat) : ?>
                        <a class="font-semibold text-purple-700" href="<?php echo esc_url(get_term_link($belowCat)); ?>"><?php echo esc_html($belowCat->name); ?></a>
                        <span class="px-2 text-slate-300">|</span>
                      <?php endif; ?>
                      <span><?php echo esc_html(human_time_diff((int) get_the_time('U'), (int) current_time('timestamp'))); ?> ago</span>
                    </div>

                    <h3 class="mt-2 line-clamp-3 text-sm font-semibold leading-snug text-slate-800">
                      <a class="group-hover:text-purple-700" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                  </div>
                </article>
              <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <button id="read-action" class="fixed top-1/2 z-50 hidden h-11 w-11 -translate-y-1/2 translate-x-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-lg hover:bg-slate-50 md:inline-flex right-[calc(50%-min(36rem,(100vw-2rem)/2))]" type="button" aria-label="Close" data-home-url="<?php echo esc_attr(home_url('/')); ?>">
        <svg class="absolute inset-0 h-11 w-11 -rotate-90" viewBox="0 0 36 36" aria-hidden="true">
          <path d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgb(226 232 240)" stroke-width="3" stroke-linecap="round" />
          <path id="read-action-progress" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgb(126 34 206)" stroke-width="3" stroke-linecap="round" stroke-dasharray="0 100" />
        </svg>

        <svg id="read-action-icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="relative h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" /></svg>
        <svg id="read-action-icon-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="relative hidden h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 12l7-7 7 7" /></svg>
      </button>
    <?php endwhile; ?>
  <?php else : ?>
    <p class="text-sm text-slate-600">No posts found.</p>
  <?php endif; ?>
</main>

</div>

<?php get_footer();
