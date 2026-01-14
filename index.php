<?php

get_header();

?>

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

      <div class="mt-10 lg:hidden">
        <div class="border-t border-slate-200 pt-6">
          <?php yahooNews_render_social_links('justify-center gap-5'); ?>

          <div class="mt-5 flex flex-wrap justify-center gap-x-4 gap-y-2 text-xs text-slate-600">
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Terms and Privacy Policy</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Privacy Dashboard</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Advertise</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">About Our Ads</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Careers</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Help</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Feedback</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Products and Services</a>
          </div>

          <p class="mt-4 text-center text-xs text-slate-500">&copy; <?php echo esc_html((string) wp_date('Y')); ?> Yahoo. All rights reserved.</p>
        </div>
      </div>
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

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
          <?php yahooNews_render_social_links('gap-4'); ?>

          <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-600">
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Terms and Privacy Policy</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Privacy Dashboard</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Advertise</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">About Our Ads</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Careers</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Help</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Feedback</a>
            <a class="no-underline hover:text-slate-900 hover:no-underline" href="#">Products and Services</a>
          </div>

          <p class="mt-4 text-xs text-slate-500">&copy; <?php echo esc_html((string) wp_date('Y')); ?> Yahoo. All rights reserved.</p>
        </div>

        <?php wp_reset_postdata(); ?>
      </div>
    </aside>
  </div>
</main>

<?php

get_footer();
