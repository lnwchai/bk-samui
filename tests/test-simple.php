<?php
echo "=== IDOR Fix Simple Test ===

";

echo "Test 1: Functions exist
";
echo "samui_generate_access_token: " . (function_exists('samui_generate_access_token') ? 'PASS' : 'FAIL') . "
";
echo "samui_get_payment_log_by_token: " . (function_exists('samui_get_payment_log_by_token') ? 'PASS' : 'FAIL') . "
";
echo "samui_backfill_payment_log_tokens: " . (function_exists('samui_backfill_payment_log_tokens') ? 'PASS' : 'FAIL') . "
";

echo "
Test 2: Callback token option
";
$token = get_option('callback_token', '');
echo "callback_token length: " . strlen($token) . "
";
echo "callback_token present: " . (!empty($token) ? 'PASS' : 'FAIL') . "
";

echo "
Test 3: Test templates removed
";
$files = glob(get_template_directory() . '/templates/payment-test*.php');
echo "Test files remaining: " . count($files) . "
";
echo "Cleanup: " . (empty($files) ? 'PASS' : 'FAIL') . "
";

echo "
=== Done ===
";
