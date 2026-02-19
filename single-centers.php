<?php get_header(); ?>
<div class="head-text">
         <?php  
            echo plant_page_title();
            echo '<hr class="is-style-wide">'; 
        ?>
</div>
<main id="main" class="site-main single-main">
<div class="c-content">
    <div class="s-img"><?php the_post_thumbnail( 'full' ); ?></div>
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            //the_plant_single_title();
            echo '<div class="single-content">';
            the_content();
            edit_post_link('EDIT', '', '', null, 'btn-edit');
            echo '</div>';
        }
    }
    ?>
</div>
<div class="c-sidebar">
        <?php
          dynamic_sidebar('centerbar');
        ?>

</div>
  
</main>

<?php
    $args = array(
        'posts_per_page' => 4,
        'post_type' => 'package',
        'orderby' => 'rand',
        'meta_key'      => 'package_center',
        'meta_value'    => $post->ID
    );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
?>
<div class="s-related">
    <h3>แพ็กเกจแนะนำ</h3>
    <div class="s-sub">
    <p>แพ็กเกจสุขภาพโดยบุคลากรทางการแพทย์เฉพาะทาง</p>
    <a href="/packages/">ดูทั้งหมด <span>›</span></a>
    </div>
   
    <div class="s-grid -d4">
            <?php 
           
            
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                get_template_part( 'parts/content', 'package' );
            }
            wp_reset_postdata();
            ?>
    </div>
</div>
<?php } ?>

<?php get_footer(); ?>
