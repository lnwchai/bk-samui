<?php
// Prevent caching of payment pages
nocache_headers();
/**
 * Template Name: Payment Callback
 */

$ref1 = isset($_GET['REF1']) ? sanitize_text_field($_GET['REF1']) : '';
$status = isset($_GET['STATUS']) ? sanitize_text_field($_GET['STATUS']) : '';

// Parse REF1 format: EPAYMENY{post_id}:{callback_token} or legacy EPAYMENY{post_id}
$parts = explode(':', $ref1);
$logid_part = $parts[0] ?? '';
$callback_token = $parts[1] ?? '';

// Extract numeric post ID
$logid_numeric = preg_replace('/[^0-9]/', '', $logid_part);

if (empty($logid_numeric) || get_post_type($logid_numeric) !== 'payment_logs') {
    status_header(404);
    echo 'Invalid payment log.';
    exit;
}

// Verify callback token
$stored_token = get_post_meta($logid_numeric, 'callback_token', true);
$global_token = get_option('callback_token', '');

if (empty($callback_token) || ($stored_token !== $callback_token && $global_token !== $callback_token)) {
    status_header(403);
    echo 'Invalid callback token.';
    exit;
}

// Rate limiting: check if this payment log was already marked as success
$current_status = get_field('status', $logid_numeric);
if ($current_status === 'success' && $status === 'COMPLETE') {
    status_header(200);
    echo 'Payment already processed.';
    exit;
}

// Update payment status
if ($status === 'COMPLETE') {
    update_field('status', 'success', $logid_numeric);
    status_header(200);
    echo 'Payment successful.';
} else {
    status_header(400);
    echo 'Payment failed.';
}
exit;
