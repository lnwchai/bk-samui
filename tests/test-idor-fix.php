<?php
/**
 * Staging test for IDOR fix
 */

echo "=== IDOR Fix Staging Test ===

";

// Test 1: Verify token helpers exist
echo "Test 1: Token helper functions
";
if (function_exists('samui_generate_access_token')) {
    echo "  [PASS] samui_generate_access_token() exists
";
} else {
    echo "  [FAIL] samui_generate_access_token() missing
";
}

if (function_exists('samui_get_payment_log_by_token')) {
    echo "  [PASS] samui_get_payment_log_by_token() exists
";
} else {
    echo "  [FAIL] samui_get_payment_log_by_token() missing
";
}

if (function_exists('samui_backfill_payment_log_tokens')) {
    echo "  [PASS] samui_backfill_payment_log_tokens() exists
";
} else {
    echo "  [FAIL] samui_backfill_payment_log_tokens() missing
";
}

// Test 2: Verify tokens were backfilled
echo "
Test 2: Token backfill verification
";
 = get_posts(array(
    'post_type' => 'payment_logs',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'meta_query' => array(
        array('key' => 'access_token', 'value' => '', 'compare' => '!=')
    )
));
echo "  Posts with access_token: " . count() . "
";

 = get_posts(array(
    'post_type' => 'payment_logs',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'meta_query' => array(
        array('key' => 'callback_token', 'value' => '', 'compare' => '!=')
    )
));
echo "  Posts with callback_token: " . count() . "
";

// Test 3: Verify test templates removed
echo "
Test 3: Test template cleanup
";
 = glob(get_template_directory() . '/templates/payment-test*.php');
if (empty()) {
    echo "  [PASS] Test templates removed
";
} else {
    echo "  [FAIL] Test templates still exist: " . implode(', ', ) . "
";
}

// Test 4: Verify callback_token option exists
echo "
Test 4: Global callback_token option
";
 = get_option('callback_token', '');
if (!empty() && strlen() === 64) {
    echo "  [PASS] callback_token option exists (64 chars)
";
} else {
    echo "  [FAIL] callback_token option missing or invalid
";
}

// Test 5: Simulate Krungsri callback with test data from EML
echo "
Test 5: Krungsri callback simulation
";
 =  ?? 0;
if ( > 0) {
     = get_post_meta(, 'callback_token', true);
     = 'EPAYMENY' .  . ':' . ;
    
     = add_query_arg(array(
        'REF1' => ,
        'STATUS' => 'COMPLETE'
    ), home_url('/callback-fgurl/'));
    
    echo "  Test post_id: 
";
    echo "  Generated REF1: 
";
    echo "  Callback URL: 
";
    echo "  [INFO] Call this URL to test callback
";
} else {
    echo "  [FAIL] No payment_logs posts found
";
}

echo "
=== Test Complete ===
";
