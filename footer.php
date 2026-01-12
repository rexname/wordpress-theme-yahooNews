    </div>

    <?php if (!is_home() && !is_front_page()) : ?>
      <footer class="site-footer" role="contentinfo">
        <div class="site-footer__inner">
          <p>&copy; <?php echo esc_html((string) wp_date('Y')); ?> <?php bloginfo('name'); ?></p>
        </div>
      </footer>
    <?php endif; ?>

    <?php wp_footer(); ?>
  </body>
</html>
