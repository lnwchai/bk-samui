<?php get_header(); ?>
<div class="head-text">
         <?php  
            echo plant_page_title();
            echo '<hr class="is-style-wide">'; 
            //get acf field
            $headline_subtitle = get_field( "headline_subtitle");
            if($headline_subtitle){ echo '<h3 class="sub-title">'.$headline_subtitle.'</h3>'; }
        ?>
</div>
<main id="main" class="site-main page-main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            echo '<div class="page-content">';
            the_content();
            edit_post_link('EDIT', '', '', null, 'btn-edit');
            echo '</div>';
        }
    }
    ?>
</main>
<?php get_footer(); ?>
