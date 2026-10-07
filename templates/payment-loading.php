<?php
// Prevent caching of payment pages
nocache_headers();
/**
 * Template Name: Payment loading
 */
$token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
$logid = isset($_GET['logid']) ? intval($_GET['logid']) : '';
$page_location = isset($_GET['pmp']) ? intval($_GET['pmp']) : '';

if ($logid !== '' && $token === '') {
    $payment_page = ($page_location == 1) ? 'e-payment-package' : 'e-payment';
    $args = array(
        'post_type' => 'payment_logs',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'AND',
            array('key' => 'entry_id', 'value' => $logid),
            array('key' => 'payment_page', 'value' => $payment_page)
        )
    );
    $redirect_post_id = get_posts($args);
    if ($redirect_post_id) {
        $redirect_token = get_post_meta($redirect_post_id[0], 'access_token', true);
        if (!$redirect_token) {
            $redirect_token = samui_generate_access_token();
            update_post_meta($redirect_post_id[0], 'access_token', $redirect_token);
        }
        wp_redirect(home_url('/e-payment/loading/?token=' . $redirect_token));
        exit;
    }
    wp_redirect('/e-payment');
    exit;
}

if ($token !== '') {
    $payment_log_id = samui_get_payment_log_by_token($token);
    if ($payment_log_id > 0) {
        $the_query = new WP_Query(array(
            'p' => $payment_log_id,
            'post_type' => 'payment_logs',
            'posts_per_page' => 1,
        ));
    }
}

if (empty($the_query) || !$the_query->have_posts()) {
    wp_redirect('/e-payment');
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
</head>
<body>
    <main>
        <?php
        while ($the_query->have_posts()) : $the_query->the_post();
            $amount_str = str_replace(',', '', get_field('amount'));
            $amount = $amount_str . '00';
            $payment_type = get_field('payment_by');
            $payment_status = get_field('status');
            $type = get_field('payment_type');
            $name = get_field('first_name');
            $phone = get_field('phone_number');
            $email = get_field('email');
            $payment_log = get_the_ID();
            $callback_token = get_field('callback_token', $payment_log);
        endwhile;
        wp_reset_postdata();
        ?>

        <?php if ($payment_status == 'success'): ?>
            Payment Complete<br><a href="/">Back To Home</a>
        <?php else: ?>
            Processing, please wait
            <script nonce="<?php echo esc_attr( $GLOBALS['samui_payment_csp_nonce'] ?? '' ); ?>">
                setTimeout(function() {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'https://www.krungsriepayment.com/EPayDefaultWeb/PaymentManager/PaymentInput.do';
                    form.style.display = 'none';

                    function addField(name, value) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value;
                        form.appendChild(input);
                    }

                    addField('MERCHANTNUMBER', '950090647');
                    addField('ORDERNUMBER', '<?php echo $payment_log; ?>');
                    addField('PAYMENTTYPE', '<?php echo $payment_type; ?>');
                    addField('AMOUNT', '<?php echo intval($amount); ?>');
                    addField('CURRENCY', '764');
                    addField('AMOUNTEXP10', '-2');
                    addField('LANGUAGE', '<?php echo (get_locale() == 'th') ? 'TH' : 'EN'; ?>');
                    addField('REF1', 'EPAYMENY<?php echo $payment_log; ?><?php if ($callback_token) echo ':'.$callback_token; ?>');
                    addField('REF2', '<?php echo esc_attr($type); ?>');
                    addField('REF3', '<?php echo esc_attr($name); ?>');
                    addField('REF4', '<?php echo esc_attr($phone); ?>');
                    addField('REF5', '<?php echo esc_attr($email); ?>');

                    document.body.appendChild(form);
                    form.submit();
                }, 2000);
            </script>
        <?php endif; ?>
    </main>
</body>
</html>