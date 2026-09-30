<?php if (!defined('ABSPATH')) exit; ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#f8f6f2" />
<?php $abaya_desc = abaya_meta_description(); if ($abaya_desc) : ?>
<meta name="description" content="<?= esc_attr($abaya_desc) ?>" />
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body class="<?= is_front_page() ? 'product-landing' : 'legal-page' ?>">
<?php wp_body_open(); ?>
<?php get_template_part('template-parts/' . (is_front_page() ? 'site-header' : 'legal-header')); ?>
