<?php
// Prevent caching of payment pages
nocache_headers();
/**
 * Template Name: Patients Details
 */

$token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
$pid = 0;

if ($token !== '') {
    $pid = samui_get_patient_by_token($token);
}

// Fallback to pid for backward compatibility
if ($pid === 0 && isset($_GET['pid'])) {
    $pid = intval($_GET['pid']);
}

if ($pid === 0 || get_post_type($pid) !== 'patient_information') {
    wp_redirect('/patient-information');
    exit;
}

// Ensure access token exists for this patient
$access_token = get_post_meta($pid, 'access_token', true);
if (!$access_token) {
    $access_token = samui_generate_patient_access_token();
    update_post_meta($pid, 'access_token', $access_token);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri()); ?>">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f7; margin: 0; padding: 20px; }
        .patients-payment { max-width: 400px; margin: 50px auto; padding: 30px; text-align: center; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .payment-qr { margin-bottom: 20px; }
        .payment-qr * { width: 100%; }
        .payment-detail { padding: 16px; border-radius: 5px; background: rgba(4,79,198,0.1); margin-bottom: 20px; text-align: left; }
        .payment-detail p { margin: 8px 0; }
        .payment-mail a { display: block; margin: 0 auto; text-align: center; font-size: 14px; width: 100%; padding: 10px; font-weight: 600; color: #fff; background: #044fc6; border-radius: 99px; text-decoration: none; }
        .payment-mail a:hover { background: #0447a3; }
    </style>
</head>
<body <?php body_class(); ?>>
    <main class="site-main">
        <div class="patients-payment">
            <div class="payment-qr" id="payment-qrcode" data-link="<?php echo esc_url(home_url().'/e-payment/?patient_token='.$access_token); ?>"></div>
            <div class="payment-detail">
                <p><strong><?php echo esc_html($prefix . ' ' . $first_name . ' ' . $last_name); ?></strong></p>
                <p>HN: <?php echo esc_html($hn); ?></p>
                <p>Amount: ฿<?php echo number_format($amount); ?></p>
            </div>
            <div class="payment-mail">
                <a href="#" class="copy-link" data-link="<?php echo esc_url(home_url().'/e-payment/?patient_token='.$access_token); ?>">Copy Link</a>
            </div>
        </div>
    </main>
</body>
</html>
