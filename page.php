<?php

get_header();

?>

<div class="bg-slate-50">
<main class="mx-auto max-w-6xl px-4 py-6">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
      <?php the_post(); ?>

      <article <?php post_class('rounded-3xl border border-slate-200 bg-white shadow-xl'); ?>>
        <div class="px-4 py-6 sm:px-8 sm:py-8">
          <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl">
            <?php the_title(); ?>
          </h1>

          <?php if (has_post_thumbnail()) : ?>
            <div class="mt-6 overflow-hidden rounded-2xl bg-slate-100">
              <?php the_post_thumbnail('large', ['class' => 'h-[220px] w-full object-cover sm:h-[320px]']); ?>
            </div>
          <?php endif; ?>

          <div class="article-content mt-8">
            <?php the_content(); ?>
          </div>
        </div>
      </article>

    <?php endwhile; ?>
  <?php endif; ?>
</main>
</div>

<?php

get_footer();

?>

