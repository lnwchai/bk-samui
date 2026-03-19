<?php

/**
 * Template Name: TEST CallBack
 */

?>

<?php
add_action('init', function () {

    if (strpos($_SERVER['REQUEST_URI'], 'callback-fgurl') !== false) {

        while (ob_get_level()) {
            ob_end_clean();
        }

        status_header(200);
        header('Content-Type: text/plain');

        $successCode = $_POST['successcode'] ?? '';
        $orderRef    = $_POST['Ref'] ?? '';
        $amount      = $_POST['amt'] ?? '';
        $currCode    = $_POST['cur'] ?? '';

        // 🔐 verify hash
        $merchantId = "950606095";
        $secretKey  = "B8cnaOBSJIypnUiq0OGjlRtJyY08NG8A";
        $payType    = "N";

        $receivedHash = $_POST['secureHash'] ?? '';

        $hashString = $merchantId . "|" . $orderRef . "|" . $currCode . "|" . $amount . "|" . $payType . "|" . $secretKey;
        $calculatedHash = hash('sha256', $hashString);

        if ($receivedHash !== $calculatedHash) {
            exit; // ❌ ไม่ผ่าน
        }

        // 🧪 log
        $log = date('Y-m-d H:i:s') . "\n";
        $log .= "OrderRef: " . $orderRef . "\n";
        $log .= print_r($_POST, true) . "\n\n";

        file_put_contents(WP_CONTENT_DIR.'/callback_log.txt', $log, FILE_APPEND);

        echo "OK";

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        if ($successCode == "0") {
            // ✅ success
        } else {
            // ❌ fail
        }

        exit;
    }

});