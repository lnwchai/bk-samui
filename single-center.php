<?php get_header(); ?>
<main id="main" class="site-main single-main">
<div class="d-content">
    <div class="s-img"><?php the_post_thumbnail( 'full' ); ?></div>


    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            //the_plant_single_title();
            echo '<div class="single-content">';
            echo '<h1>'.get_the_title().'</h1>';
            echo '<p>'.the_field('specialty').'</p>';
            echo '<p>'.the_field('language').'</p>';
            echo '<p>'.the_field('Education').'</p>';
            echo '</div>';
            the_content();
            
        }
    }
    ?>



</div>


  
</main>
<div class="s-consult">
    <h2>ปรึกษาแพทย์ออนไลน์</h2>
    <p>บริการตรวจรักษาออนไลน์ พร้อมบริการส่งยาถึงบ้านคุณอย่างต่อเนื่อง 
โรงพยาบาลกรุงเทพสมุย “พัฒนาไม่หยุด สู่ขีดสุดของการดูแล”</p>
    <a href="#">ดูรายละเอียด <span>›</span></a>
  
</div>
<?php get_footer(); ?>
