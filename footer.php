<?php if (!defined('ABSPATH')) exit; ?>
<?php if (is_front_page()) : ?>
<?php get_template_part('template-parts/site-footer'); ?>
    <div class="sticky-bar-container">
      <a class="sticky-cta-btn" href="#order-form-section">اضغطي هنا للطلب</a>
    </div>
<?php else : ?>
<?php get_template_part('template-parts/legal-footer'); ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
