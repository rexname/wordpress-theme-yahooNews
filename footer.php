    </div>

    <?php $hideFooterOnDesktop = is_home() || is_front_page() || is_category(); ?>

    <footer class="border-t border-slate-200 bg-slate-50 <?php echo $hideFooterOnDesktop ? 'lg:hidden' : ''; ?>" role="contentinfo">
      <div class="mx-auto max-w-6xl px-4 py-8">
        <?php yahooNews_render_footer_links('justify-center', 'justify-center', 'text-center'); ?>
      </div>
    </footer>

    <?php wp_footer(); ?>
  </body>
</html>
