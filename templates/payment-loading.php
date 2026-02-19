<?php

/**
 * Template Name: Payment loading
 */
$logid = isset($_GET['logid']) ? intval($_GET['logid']) : '';
$page_location = isset($_GET['pmp']) ? intval($_GET['pmp']) : '';
if($logid == ''){
    wp_redirect( '/e-payment' );
}
?>
<?php get_header(); ?>

<main id="main" class="site-main page-main">
       
    <?php
    $orderid = $logid;
    $amount = '';
    $payment_type = '';
    $type = '';
    $name = '';
    $phone = '';
    $email = '';
    $payment_log = '';
    $payment_status = '';

    if( $page_location == 1 ){
        $payment_page = 'e-payment-package';
    }else{
        $payment_page = 'e-payment';
    }

    $args = array(
        'post_type' => 'payment_logs',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'entry_id',
                'value' => $logid
            ),
            array(
                'key' => 'payment_page',
                'value' => $payment_page
            )
        )
    );
    $the_query = new WP_Query( $args );
    while ( $the_query->have_posts() ) : $the_query->the_post();         
        $amount_str = str_replace(',','',get_field('amount'));
        $amount = $amount_str.'00';
        $payment_type = get_field('payment_by');
        $payment_status = get_field('status');
        $type = get_field('payment_type');
        $name = get_field('first_name');
        $phone = get_field('phone_number');
        $email = get_field('email');
        $payment_log = get_the_ID();
    endwhile; wp_reset_postdata();
    ?>

    <?php if( $payment_status == 'success' ): ?>
        <div class="payment-action">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="50px" height="50px" fill-rule="nonzero"><g fill="#35cc27" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,2c-12.682,0 -23,10.318 -23,23c0,12.683 10.318,23 23,23c12.683,0 23,-10.317 23,-23c0,-12.682 -10.317,-23 -23,-23zM35.827,16.562l-11.511,16.963l-8.997,-8.349c-0.405,-0.375 -0.429,-1.008 -0.053,-1.413c0.375,-0.406 1.009,-0.428 1.413,-0.053l7.29,6.764l10.203,-15.036c0.311,-0.457 0.933,-0.575 1.389,-0.266c0.458,0.31 0.577,0.932 0.266,1.39z"></path></g></g></svg>
            </div>
            <div class="info">
                <h3>Payment Complete</h3>
                <a href="/">Back To Home</a>
            </div>
        </div>
    <?php else: ?>
        <div class="payment-action">
            <div class="icon">
                <img src="/wp-content/uploads/2023/03/loading-circle.gif" alt="" srcset="">
            </div>
            <div class="info">
                <h3>Processing, please wait</h3>
            </div>
        </div>
        <form class="hide" method="POST" action="https://www.krungsriepayment.com/EPayDefaultWeb/PaymentManager/PaymentInput.do">
            <p>Please complete the form.</p>
            MERCHANTNUMBER
            <input type="text" name="MERCHANTNUMBER" id="MERCHANTNUMBER" size="9" maxlength="9" value="950090647"><br>
            ORDERNUMBER
            <input type="text" name="ORDERNUMBER" id="ORDERNUMBER" size="15" maxlength="15" value="<?php echo $logid; ?>"><br>
            PAYMENTTYPE
            <select name="PAYMENTTYPE">
                <option value="CreditCard" <?php echo $payment_type == 'CreditCard' ? 'selected' : ''; ?>>CreditCard</option>
                <option value="DirectDebit" <?php echo $payment_type != 'CreditCard' ? 'selected' : ''; ?>>DirectDebit</option>
            </select><br>
            AMOUNT
            <input type="text" name="AMOUNT" id="AMOUNT" size="20" maxlength="20" value="<?php echo intval($amount); ?>"><br>
            CURRENCY
            <input type="text" name="CURRENCY" id="CURRENCY" size="20" maxlength="20" value="764"><br>
            EXP
            <input type="text" name="AMOUNTEXP10" id="AMOUNTEXP10" size="20" maxlength="20" value="-2"><br>
            LANGUAGE

            <input size="20" type="text" name="LANGUAGE" value="<?php 
            $current_language = get_locale();

            if( $current_language == 'en_US' ){
            echo 'EN';
            }
            
            if( $current_language == 'th' ){
            echo 'TH';
            }
            ?>"><br>
            REF1 <input size="20" type="text" name="REF1" value="EPAYMENY<?php echo $payment_log; ?>"><br>
            REF2 <input size="20" type="text" name="REF2" value="<?php echo $type; ?>"><br>
            REF3 <input size="20" type="text" name="REF3" value="<?php echo $name; ?>"><br>
            REF4 <input size="20" type="text" name="REF4" value="<?php echo $phone; ?>"><br>
            REF5 <input size="20" type="text" name="REF5" value="<?php echo $email; ?>">
            <p>
                <input type="submit" class="pay-button" name="SUBMIT" value="Submit">
                <input type="reset" name="RESET" value="Reset">
            </p>
        </form>
        <script>
            window.onload = setTimeout( function(){
                const payBtn = document.querySelector('.pay-button');
                if( payBtn != null ){
                    payBtn.click();
                }
            }, 3000);
        </script>
    <?php endif; ?>
</main>
<?php get_footer(); ?>