<?php

/**
 * Template Name: Payment test form
 */
if (!current_user_can('read')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$logid = isset($_GET['REF1']) ? sanitize_text_field($_GET['REF1']) : '';
$status = isset($_GET['STATUS']) ? sanitize_text_field($_GET['STATUS']) : '';
?>
<?php get_header(); ?>
<main id="main" class="site-main page-main">
    
<form name="payFormCcard" method="post" action=" https://uat.krungsrieasypay.com/bay/eng/payment/payForm.jsp">
<input type="hidden" name="merchantId" value=" 950606095">
<input type="hidden" name="amount" value="3000.0" >
<input type="hidden" name="orderRef" value="000000000014">
<input type="hidden" name="currCode" value="764" >
<input type="hidden" name="successUrl" value="https://bangkokhospitalsamui.com/successurl/">
<input type="hidden" name="failUrl" value="https://bangkokhospitalsamui.com/en/e-payment/callback-fgurl/">
<input type="hidden" name="cancelUrl" value="https://bangkokhospitalsamui.com/cancelurl/">
<input type="hidden" name="payType" value="N">
<input type="hidden" name="payMethod" value="ALL">
<input type="hidden" name="lang" value="E">
<input type="hidden" name="secureHash" value="xsdsperyeyrpwerhpr">
<input type="submit" name="submit">
</form>
</main>
<?php get_footer(); ?>