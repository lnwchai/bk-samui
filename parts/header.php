<style>
    .site-member {
        height: 32px;
    }
    .site-member img, .site-member svg {
        width: 32px;
        height: 32px;
    }
</style>
<header id="masthead" class="site-header">
    <div class="s-container">
        <div class="site-action -left">
            <?php echo plant_actions('left'); ?>
        </div>
        <div class="site-branding -center">
            <?php echo plant_logo() . plant_title(); ?>
        </div>
        <nav class="nav-panel <?php echo plant_nav_position()?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                ]
            );
            ?>
        </nav>
        <div class="site-action -right">
            <?php echo plant_actions('right'); ?>
        </div>
    </div>
</header>
<div class="search-panel">
    <div class="s-container">
        <?php echo plant_search_form(); ?>
    </div>
</div>
<div class="site-header-space"></div>