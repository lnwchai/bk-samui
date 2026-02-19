<?php get_header(); ?>
<main id="main" class="site-main single-main">
<div class="s-head">
    <div class="s-img"><?php the_post_thumbnail( 'full' ); ?></div>
    <h1><?php the_title(); ?></h1>
    <div class="s-date-share">
        <div class="s-date">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_69_1387)">
            <path d="M15.5102 1.9162H13.3878V1.76017C13.3878 1.16102 12.8718 0.667969 12.2449 0.667969C11.618 0.667969 11.102 1.16102 11.102 1.76017V1.9162H4.89796V1.76017C4.89796 1.16102 4.38204 0.667969 3.7551 0.667969C3.12816 0.667969 2.61224 1.16102 2.61224 1.76017V1.9162H0.489796C0.222041 1.9162 0 2.12839 0 2.38428V14.2424C0 14.8416 0.515918 15.3346 1.14286 15.3346H14.8571C15.4841 15.3346 16 14.8416 16 14.2424V2.38428C16 2.12839 15.778 1.9162 15.5102 1.9162ZM12.0816 1.76017C12.0816 1.67279 12.1535 1.60414 12.2449 1.60414C12.3363 1.60414 12.4082 1.67279 12.4082 1.76017V3.00839C12.4082 3.18315 12.0816 3.18315 12.0816 3.00839V1.76017ZM3.59184 1.76017C3.59184 1.67279 3.66367 1.60414 3.7551 1.60414C3.84653 1.60414 3.91837 1.67279 3.91837 1.76017V3.00839C3.91837 3.18315 3.59184 3.18315 3.59184 3.00839V1.76017ZM2.61224 2.85237V3.00839C2.61224 3.60754 3.12816 4.10059 3.7551 4.10059C4.38204 4.10059 4.89796 3.60754 4.89796 3.00839V2.85237H11.102V3.00839C11.102 3.60754 11.618 4.10059 12.2449 4.10059C12.8718 4.10059 13.3878 3.60754 13.3878 3.00839V2.85237H15.0204V5.66088H0.979592V2.85237H2.61224ZM14.8571 14.3985H1.14286C1.05143 14.3985 0.979592 14.3298 0.979592 14.2424V6.59705H15.0204V14.2424C15.0204 14.3298 14.9486 14.3985 14.8571 14.3985Z" fill="#B21E28"/></g>
            <defs><clipPath id="clip0_69_1387"><rect width="16" height="14.6667" fill="white" transform="translate(0 0.667969)"/></clipPath></defs></svg>
            <?php echo get_the_date(); ?>               
        </div>
        <div class="s-share">
            <?php echo do_shortcode('[seed_social]'); ?>  
        </div>
    </div>
    <hr>
</div>
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
  
</main>
<div class="s-related">
    <h3>บทความสุขภาพ</h3>
    <div class="s-sub">
    <p>ความรู้ด้านสุขภาพโดยผู้เชี่ยวชาญจากโรงพยาบาลกรุงเทพสมุย</p>
    <a href="#">ดูทั้งหมด <span>›</span></a>
    </div>
   

    <div class="s-grid -d3">
            <?php 
            $args = array(
                'category_name' => 'news',
                'posts_per_page' => 3,
                'orderby'=> 'rand'
            );
            $the_query = new WP_Query( $args );
            
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                get_template_part( 'parts/content', 'card' );
            }
            wp_reset_postdata();
            ?>
    </div>
</div>
<?php get_footer(); ?>
