<?php
if (!defined('ABSPATH')) exit;
get_header();
?>
<main class="legal-content policy-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <p class="legal-eyebrow">ABAYA COLLECTION</p>
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
<?php endwhile; else : ?>
  <p class="legal-eyebrow">ABAYA COLLECTION</p>
  <h1>الصفحة غير موجودة</h1>
  <section><p><a href="/">العودة إلى الصفحة الرئيسية</a></p></section>
<?php endif; ?>
</main>
<?php get_footer(); ?>
