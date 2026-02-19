<?php

/**
 * Template Name: Doctor
 */

?>
<?php get_header(); ?>

<main id="main" class="site-main page-main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            if (!get_field('hide_title')) {
                echo plant_page_title();
            }
            echo '<div class="page-content">';
            the_content();
            edit_post_link('EDIT', '', '', null, 'btn-edit');
            echo '</div>';
        }
    }
    ?>
</main>
<?php get_footer(); ?>