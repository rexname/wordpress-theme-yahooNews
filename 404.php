<?php

get_header();

$label = get_query_var('yahoonews_unavailable_label');
if (!is_string($label)) {
    $label = '';
}

?>

<div class="bg-slate-50">
<main class="mx-auto max-w-6xl px-4 py-6">
  <article class="rounded-3xl border border-slate-200 bg-white shadow-xl">
    <div class="px-4 py-6 sm:px-8 sm:py-10">
      <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl">
        404 – Page not found
      </h1>

      <?php if ($label !== '') : ?>
        <p class="mt-3 text-sm text-slate-600">
          <?php echo esc_html($label); ?>
        </p>
      <?php endif; ?>

      <p class="mt-4 text-sm text-slate-600">
        The page you are looking for is not available or has been removed.
      </p>

      <div class="mt-6 flex flex-wrap items-center gap-3">
        <a class="inline-flex items-center justify-center rounded-full bg-purple-700 px-4 py-2 text-sm font-semibold text-white no-underline hover:bg-purple-800 hover:no-underline" href="<?php echo esc_url(home_url('/')); ?>">
          Back to home
        </a>
      </div>
    </div>
  </article>
</main>
</div>

<?php

get_footer();

?>

