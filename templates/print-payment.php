<?php
// Prevent caching of payment pages
nocache_headers();
/**
 * Template Name: Print Payment
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
    wp_redirect('/');
    exit;
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
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #fff; margin: 0; padding: 20px; color: #333; }
        .print-area { max-width: 800px; margin: 0 auto; padding: 40px; }
        .print-area h1 { font-size: 24px; margin-bottom: 20px; color: #002d73; }
        .print-area p { margin: 8px 0; font-size: 16px; }
        .print-area .amount { font-size: 32px; font-weight: bold; color: #0447a3; margin: 20px 0; }
        .print-area .qr { margin: 20px 0; text-align: center; }
        .print-area .qr img { width: 200px; height: 200px; }
        @media print {
            body { padding: 0; }
            .print-area { padding: 0; }
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <main class="site-main">
        <div class="print-area">
            <h1>E-Payment Slip</h1>
            <p><strong>Name:</strong> <?php echo esc_html($prefix . ' ' . $first_name . ' ' . $last_name); ?></p>
            <p><strong>HN:</strong> <?php echo esc_html($hn); ?></p>
            <p><strong>Amount:</strong> ฿<?php echo number_format($amount); ?></p>
            <div class="qr">
                <div class="payment-qr" id="payment-qrcode" data-link="<?php echo esc_url(home_url().'/e-payment/?token=' . $token); ?>"></div>
            </div>
            <p class="amount">฿<?php echo number_format($amount); ?></p>
            <p><em>Please scan QR code or use the link below to complete payment:</em></p>
            <p><?php echo esc_url(home_url().'/e-payment/?token=' . $token); ?></p>
        </div>
    </main>
</body>
</html>
