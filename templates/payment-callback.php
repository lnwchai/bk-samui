<?php
// Prevent caching of payment pages
nocache_headers();
/**
 * Template Name: Payment Callback
 */

 add_action('init', function () {
 
     if (strpos($_SERVER['REQUEST_URI'], 'callback-fgurl') !== false) {
 
         // รับค่าจากธนาคาร
         $successCode = $_POST['successcode'] ?? '';
         $orderRef    = $_POST['Ref'] ?? '';
         $amount      = $_POST['amt'] ?? '';
 
         // ✅ ต้องตอบ OK ก่อน
         echo "OK";
         flush();
 
         // 🔐 TODO: verify secureHash
 
         if ($successCode == "0") {
             // ✅ success
             // update DB = paid
         } else {
             // ❌ failed
             // update DB = failed
         }
 
         exit;
     }
 
 });
 
