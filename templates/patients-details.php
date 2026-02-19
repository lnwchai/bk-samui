<?php
/**
 * Template Name: Patients Details
 */

if (!current_user_can('read')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$pid = isset($_GET['pid']) ? sanitize_text_field($_GET['pid']) : '';
if( $pid == '' ) wp_redirect( '/patient-information' );

$prefix = get_field('prefix', $pid);
$first_name = get_field('first_name', $pid);
$last_name = get_field('last_name', $pid);
$hn = get_field('hn', $pid); 
$amount = get_field('amount', $pid);

get_header(); 
?>
<main id="main" class="site-main page-main">
    <div class="patients-payment">
        <div class="payment-qr" id="payment-qrcode" data-link="<?php echo home_url().'/e-payment/?prefix='.$prefix.'&fname='.$first_name.'&lname='.$last_name.'&hn='.$hn.'&amount='.$amount; ?>"></div>
        <div class="payment-detail">
            <p><b>Name :</b> <?php echo $prefix.' '.$first_name.' '.$last_name; ?></p>
            <p><b>HN :</b> <?php echo $hn; ?></p>
            <p><b>Amount :</b> <?php echo number_format($amount,2); ?></p>
        </div>
        <div class="payment-mail s-grid -m2">
            <a href="#" class="copy-link" data-link="<?php echo home_url().'/e-payment/?prefix='.$prefix.'&fname='.$first_name.'&lname='.$last_name.'&hn='.$hn.'&amount='.$amount; ?>">Copy Link</a>
            <a href="/patients-details/print-payment?pid=<?php echo $pid; ?>" class="" target="_blank">Print</a>
        </div>
    </div>
</main>
<?php get_footer(); ?>