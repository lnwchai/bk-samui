<?php

/**
 * Template Name: Print Payment
 */
$pid = isset($_GET['pid']) ? sanitize_text_field($_GET['pid']) : '';
if( $pid == '' ){
    wp_redirect( '/' );
}
$prefix = get_field('prefix', $pid);
$first_name = get_field('first_name', $pid);
$last_name = get_field('last_name', $pid);
$hn = get_field('hn', $pid); 
$amount = get_field('amount', $pid);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <style type="text/css">
    body.coming-soon {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        padding: 20px;
    }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class('coming-soon'); ?>>
    <a class="skip-link screen-reader-text" href="#content">
        <?php esc_html_e('Skip to content', 'plant'); ?>
    </a>
    <main id="main" class="site-main m-0">
        <div class="patients-payment">
            <div class="payment-qr" id="payment-qrcode" data-link="<?php echo home_url().'/e-payment/?prefix='.$prefix.'&fname='.$first_name.'&lname='.$last_name.'&hn='.$hn.'&amount='.$amount; ?>"></div>
            <div class="payment-detail">
                <p><b>Name :</b> <?php echo $prefix.' '.$first_name.' '.$last_name; ?></p>
                <p><b>HN :</b> <?php echo $hn; ?></p>
                <p><b>Amount :</b> <?php echo number_format($amount); ?></p>
            </div>
        </div>
        <script>
            var css =
                '@page { size: portrait; }@media print { html, body { width: 100%; height:100%; margin: 0 !important; padding: 0 !important; overflow: hidden}}',
                head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');
        
            style.type = 'text/css';
            style.media = 'print';
        
            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
        
            head.appendChild(style);
            
            setTimeout(() => {
                window.print();
            }, 1000);
           
        </script>
    </main>
    <?php wp_footer(); ?>
</body>

</html>