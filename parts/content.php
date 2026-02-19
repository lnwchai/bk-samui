<article id="post-<?php the_ID(); ?>" <?php post_class('s-content'); ?>>    
        <?php if (has_post_thumbnail()) : ?>
            <div class="entry-pic">
                <?php echo plant_cat('-btn'); ?>
                <a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail('medium_large'); ?>
                </a>
            </div>
        <?php endif; ?>
    <div class="entry-info">
        <header>
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h2>'); ?>
            <div class="entry-meta">
            <?php echo plant_date(); ?>
        </div>
        </header>
        <div class="entry-excerpt">
            <?php the_excerpt();?>
        </div>
    </div>
</article>