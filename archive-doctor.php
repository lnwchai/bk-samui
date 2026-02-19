<?php get_header(); ?>
<main id="main" class="site-main">
    <header class="page-header alignwide <?php echo plant_page_title_css(); ?>">
        <?php the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description _space">', '</div>'); ?>
    </header>
    <?php
    if (have_posts()) {
        echo '<div class="s-grid ' . plant_grid_columns_css() . '">';
        while (have_posts()) {
            the_post();
            get_template_part('parts/content', plant_content_template());
        }
        echo '</div>';
        echo plant_paging();
    } else {
        get_template_part('parts/content', 'none');
    }
    ?>
</main>
<?php get_footer(); ?>