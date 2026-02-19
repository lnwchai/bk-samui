<style>
    @media(min-width: 1024px) {

        .site-nav,
        .search-panel {
            --s-head-text: var(--s-nav-text);
            --s-head-hover: var(--s-nav-hover);
        }

        .site-header .s-container {
            height: unset;
        }

        .site-header .s-container>div {
            display: flex;
            align-items: center;
        }

        .header-classic>.s-container {
            height: calc(var(--s-head-height) - var(--s-nav-height));
        }

        .site-header-center {
            margin: 0 auto;
        }

        .site-header-right {
            margin-left: auto;
        }

        .nav-inline {
            margin-left: unset;
            height: var(--s-nav-height);
        }

        .site-nav,
        .s-nav .sub-menu,
        .search-panel {
            background: var(--s-nav-bg);
        }

        .s-nav .sub-menu::before {
            border-bottom-color: var(--s-nav-bg);
        }

        .s-nav,
        .s-nav a,
        .search-panel {
            color: var(--s-nav-text);
        }

        .s-nav li:hover>a,
        .s-nav li:hover>.i-down,
        .s-nav .sub-menu li:hover>a,
        .s-nav .sub-menu li:hover>.i-down {
            color: var(--s-nav-hover);
        }

        .site-action {
            margin-left: auto;
        }
    }
</style>
<header id="masthead" class="site-header header-classic">
    <div class="s-container">
        <div class="site-branding -left">
            <?php echo plant_logo() . plant_title(); ?>
        </div>
        <div class="site-header-right _desktop">
            <?php
            $right = get_theme_mod('header_top_right_code', '[s_social]');
            echo do_shortcode($right);
            ?>
        </div>
        <div class="site-action -right _mobile">
            <?php echo plant_actions('right'); ?>
            <div class="nav-toggle -main"><em></em></div>
        </div>
    </div>
    <div class="site-nav">
        <div class="s-container">
            <nav class="nav-panel -right nav-inline">
                <?php dynamic_sidebar('before_nav'); ?>
                <?php wp_nav_menu(['theme_location' => 'primary','menu_class'     => 's-nav']); ?>
                <?php dynamic_sidebar('after_nav');?>
            </nav>
            <div class="site-action _desktop">
                <?php echo plant_actions('right'); ?>
            </div>
        </div>
    </div>
    
</header>
<nav class="nav-panel -right">
    <div class="nav-toggle nav-close"><em></em></div>
    <?php dynamic_sidebar('before_nav'); ?>
    <?php if (has_nav_menu('mobile')) {
        wp_nav_menu(['theme_location' => 'mobile',]);
    } else {
        wp_nav_menu(['theme_location' => 'primary',]);
    }?>
    <?php dynamic_sidebar('after_nav');?>
</nav>
<div class="search-panel">
    <div class="s-container">
        <?php echo plant_search_form(); ?>
    </div>
</div>
<div class="site-header-space"></div>