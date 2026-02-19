<?php

/**
 * Template Name: Payment Callback
 */
if (!current_user_can('read')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$logid = isset($_GET['REF1']) ? sanitize_text_field($_GET['REF1']) : '';
$status = isset($_GET['STATUS']) ? sanitize_text_field($_GET['STATUS']) : '';
?>
<?php get_header(); ?>
<main id="main" class="site-main page-main">
    <?php
    if( $logid != '' && $status != '' ):
        date_default_timezone_set('Asia/Bangkok');
        $date = date('d-m-Y H:i');
        ?>
        <div class="payment-action">
            <?php if( $status == 'COMPLETE' ) :  
                update_field( 'status', 'success', $logid );
                update_field( 'date_success', $date, $logid ); ?>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="50px" height="50px" fill-rule="nonzero"><g fill="#35cc27" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,2c-12.682,0 -23,10.318 -23,23c0,12.683 10.318,23 23,23c12.683,0 23,-10.317 23,-23c0,-12.682 -10.317,-23 -23,-23zM35.827,16.562l-11.511,16.963l-8.997,-8.349c-0.405,-0.375 -0.429,-1.008 -0.053,-1.413c0.375,-0.406 1.009,-0.428 1.413,-0.053l7.29,6.764l10.203,-15.036c0.311,-0.457 0.933,-0.575 1.389,-0.266c0.458,0.31 0.577,0.932 0.266,1.39z"></path></g></g></svg>
                </div>
                <div class="info">
                    <h3>Payment Complete</h3>
                    <a href="/">Back To Home</a>
                </div>
            <?php else: ?>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
                        <path fill="#f44336" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"></path><path fill="#fff" d="M29.656,15.516l2.828,2.828l-14.14,14.14l-2.828-2.828L29.656,15.516z"></path><path fill="#fff" d="M32.484,29.656l-2.828,2.828l-14.14-14.14l2.828-2.828L32.484,29.656z"></path>
                    </svg>
                </div>
                <div class="info">
                    <h3>Payment Cancel</h3>
                    <a href="/">Back To Home</a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    endif;
    ?>
    <br><br>
</main>
<?php get_footer(); ?>