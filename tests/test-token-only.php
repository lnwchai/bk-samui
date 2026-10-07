<?php
// Test 1: Valid token returns payment log
$token = "2SKKoAumEtJldYIo8OmJNNpTJ7ttOP75";
$post_id = samui_get_payment_log_by_token($token);
echo "Valid token test: " . ($post_id > 0 ? "PASS (post_id=$post_id)" : "FAIL") . "\n";

// Test 2: Invalid token returns 0
$invalid_token = "invalid";
$post_id = samui_get_payment_log_by_token($invalid_token);
echo "Invalid token test: " . ($post_id === 0 ? "PASS" : "FAIL") . "\n";

// Test 3: Empty token returns 0
$post_id = samui_get_payment_log_by_token("");
echo "Empty token test: " . ($post_id === 0 ? "PASS" : "FAIL") . "\n";

// Test 4: Verify template does not accept logid
$template = file_get_contents(get_stylesheet_directory() . "/templates/payment-loading.php");
$has_logid_fallback = strpos($template, "logid") !== false;
echo "Legacy logid removed: " . (!$has_logid_fallback ? "PASS" : "FAIL") . "\n";
