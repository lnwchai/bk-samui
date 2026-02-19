<?php 
    $docter_id = get_the_ID();
    $center_id = get_field('center_post');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('s-content'); ?>>    
    <div class="pic-doc">
        <?php if(has_post_thumbnail()) { the_post_thumbnail('medium_large');} else { echo '<img src="/wp-content/uploads/2023/05/thumb-doctor.png" alt="'. get_the_title() .'" />'; }?>
    </div>
    <div class="entry-info">
        <header>
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h2>');  ?>
            <div class="entry-meta">
                <?php the_field('specialty') ?>
            </div>
        </header>

        <div class="bottom">
            <div class="s-bt -red">
                <?php 
                    if($center_id[0]): 
                    $appointment_link = '/appointment/?cn='.$center_id[0].'&dt='.$docter_id;
                ?>
                    <a href="<?php echo $appointment_link; ?>">นัดแพทย์</a>
                <?php endif; ?>
            </div>

            <div class="s-bt -blue">
                <a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>"> ดูรายละเอียด <span>›</span></a>
            </div>
        </div>
        
    </div>
    
</article>