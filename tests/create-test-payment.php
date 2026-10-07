<?php
echo "=== Creating Test Payment Log from EML Data ===

";

// Generate random 16-digit invoice reference with TESTSEC prefix
$invoice_ref = 'TESTSEC' . wp_rand(1000000000000000, 9999999999999999);
echo "Invoice Reference: $invoice_ref
";

// Random amount above 1000
$amount = wp_rand(1001, 9999999);
echo "Amount: $amount
";

// Create payment_logs post
$post_id = wp_insert_post(array(
    'post_title' => '#TEST-' . $invoice_ref . ' Test Patient',
    'post_type' => 'payment_logs',
    'post_status' => 'publish',
    'post_author' => 1,
));

if (is_wp_error($post_id)) {
    echo "[FAIL] Could not create test post: " . $post_id->get_error_message() . "
";
    exit(1);
}

echo "Created payment_logs post ID: $post_id
";

// Update ACF fields
update_field('entry_id', $invoice_ref, $post_id);
update_field('amount', $amount, $post_id);
update_field('payment_by', 'CreditCard', $post_id);
update_field('payment_type', 'PatientPayment', $post_id);
update_field('first_name', 'Test', $post_id);
update_field('last_name', 'Patient', $post_id);
update_field('phone_number', '089-000-0000', $post_id);
update_field('email', 'test@example.com', $post_id);
update_field('status', 'pending', $post_id);
update_field('payment_page', 'e-payment', $post_id);

// Generate callback token
$callback_token = samui_generate_access_token();
update_post_meta($post_id, 'callback_token', $callback_token);
update_post_meta($post_id, 'access_token', samui_generate_access_token());

echo "[PASS] Test payment log created
";
echo "  entry_id: $invoice_ref
";
echo "  callback_token: $callback_token
";

// Generate test URLs
echo "
=== Test URLs ===
";
$access_token = get_post_meta($post_id, 'access_token', true);
echo "Payment loading (token): " . home_url("/e-payment/loading/?token=$access_token") . "
";
echo "Callback URL: " . add_query_arg(array(
    'REF1' => 'EPAYMENY' . $post_id . ':' . $callback_token,
    'STATUS' => 'COMPLETE'
), home_url('/callback-fgurl/')) . "
";

echo "
=== Red Team Tests ===
";

// Test 1: Access with invalid token should fail
echo "
Test 1: Invalid token access
";
$invalid_token = 'invalid_token_12345';
$result = samui_get_payment_log_by_token($invalid_token);
echo "  Invalid token returns ID: " . $result . "
";
echo "  Expected: 0
";
echo "  " . ($result === 0 ? 'PASS' : 'FAIL') . "
";

// Test 2: Access with valid token should work
echo "
Test 2: Valid token access
";
$valid_id = samui_get_payment_log_by_token($access_token);
echo "  Valid token returns ID: " . $valid_id . "
";
echo "  Expected: $post_id
";
echo "  " . ($valid_id === $post_id ? 'PASS' : 'FAIL') . "
";

// Test 3: Callback with invalid token should fail
echo "
Test 3: Callback with invalid token
";
echo "  [INFO] Calling callback with invalid token should return 403
";

// Test 4: Callback with valid token should succeed
echo "
Test 4: Callback with valid token
";
echo "  [INFO] Calling callback with valid token should mark payment as success
";

echo "
=== Next Steps ===
";
echo "1. Visit payment loading URL to verify form
";
echo "2. Call callback URL to test payment completion
";
echo "3. Verify status changed to 'success'
";
