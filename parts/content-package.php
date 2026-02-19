<?php
 $i_arrow = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
 <path d="M5.29279 1.63654C5.10532 1.82406 5 2.07837 5 2.34354C5 2.6087 5.10532 2.86301 5.29279 3.05054L10.2428 8.00054L5.29279 12.9505C5.11063 13.1391 5.00983 13.3917 5.01211 13.6539C5.01439 13.9161 5.11956 14.1669 5.30497 14.3524C5.49038 14.5378 5.74119 14.6429 6.00339 14.6452C6.26558 14.6475 6.51818 14.5467 6.70679 14.3645L12.3638 8.70754C12.5513 8.52001 12.6566 8.2657 12.6566 8.00054C12.6566 7.73537 12.5513 7.48106 12.3638 7.29354L6.70679 1.63654C6.51926 1.44907 6.26495 1.34375 5.99979 1.34375C5.73462 1.34375 5.48031 1.44907 5.29279 1.63654Z" fill="white"/>
 </svg>
 ';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('s-content'); ?>>    
        <?php if (has_post_thumbnail()) : ?>
            <div class="entry-pic">
                <a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail('full'); ?>
                </a>
            </div>
        <?php endif; ?>
    <div class="entry-info">
        <header>
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h2>'); ?>
            
        </header>
        <?php
        echo '<div class="card-package">';
        echo '<div class="p-price">'.get_field('normal_price').'<b>฿</b></div>';
        echo '<a href="/e-payment-package/?p-name='.get_the_title().'&p-price='.get_field('normal_price').'" class="s-bt">จองแพ็กเกจ '.$i_arrow.' </a>';
        echo '</div>';
        ?>
    </div>
  
</article>