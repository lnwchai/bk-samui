<?php

/**
 * Template Name: Coming Soon soon
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <style type="text/css">
    body.coming-soon {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center; 
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        padding: 20px;
    }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class('coming-soon'); ?>>
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e('Skip to content', 'plant'); ?>
    </a>
    <main id="main" class="site-main m-0">
        <?php
        while (have_posts()) {
            the_post();
            the_content();
            edit_post_link('EDIT', '', '', null, 'btn-edit');
        }
        ?>
    </main>
    <?php wp_footer(); ?>
</body>

</html>