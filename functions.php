<?php
/**
 * Override seed_setup()
 *
 * @return void
 */
function fruit_setup(): void {
	add_theme_support( 'custom-logo', array(
		'width'       => 200,
		'height'      => 200,
		'flex-width' => true,
		) );
}
add_action( 'after_setup_theme', 'fruit_setup', 11);

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function fruit_scripts(): void {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style('fruit', get_theme_file_uri('/css/style.css'), array(), $theme_version );
	wp_enqueue_script('fruit', get_theme_file_uri('/js/main.js'), array(),  $theme_version, true);
	
    // QR Code
	wp_enqueue_script( 'qr', get_theme_file_uri('/js/qrcode.js'), array(), $theme_version, true );
	wp_enqueue_script( 'qrmin', get_theme_file_uri('/js/qrcode.min.js'), array(), $theme_version, true );
}
add_action( 'wp_enqueue_scripts', 'fruit_scripts' , 20 );



/**
 * Register widget area.
 */
function seed_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Center bar', 'plant'),
        'id'            => 'centerbar',
        'description'   => '',
        'before_widget' => '<aside id="%1" class="widget %2">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ),
    );
    register_sidebar(array(
        'name'          => esc_html__('Header Top Right', 'plant'),
        'id'            => 'header_top_right',
        'description'   => '',
        'before_widget' => '<aside id="%1" class="widget %2">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ),
    );

}
add_action('widgets_init', 'seed_widgets_init');

/**
 * Add Krungsri E-Payment menu to admin dashboard.
 *
 * @return void
 */
function krusri_e_payment_bgurl_menu(): void {
    add_menu_page(
        __( 'Krungsri E-Payment' ),
        __( 'Krungsri E-Payment' ),
        'manage_options',
        'krungsri-e-payment',
        'krusri_e_payment_bgurl_page_contents',
        'dashicons-schedule',
        100
    );
}
add_action( 'admin_menu', 'krusri_e_payment_bgurl_menu' );

function krusri_e_payment_bgurl_page_contents() {}

function set_external_url_post_link( string $post_link, WP_Post $post ): string {
    if ( 'patient_information' === $post->post_type ) {
        $pid = $post->ID;
        $access_token = get_post_meta( $pid, 'access_token', true );
        if ( ! $access_token ) {
            $access_token = samui_generate_patient_access_token();
            update_post_meta( $pid, 'access_token', $access_token );
        }
        $external_url = home_url().'/patients-details?token='.$access_token;
        return $external_url;
    }
    return $post_link;
}
add_filter( 'post_type_link', 'set_external_url_post_link', 10, 2 );


/**
 * Gravity Forms. Start
 */

//change label in form4
add_filter( 'gform_pre_render_4', 'change_label_form_4_24' );
add_filter( 'gform_pre_validation_4', 'change_label_form_4_24' );
add_filter( 'gform_pre_submission_filter_4', 'change_label_form_4_24' );
add_filter( 'gform_admin_pre_render_4', 'change_label_form_4_24' );
function change_label_form_4_24( array $form ): array {
  
    foreach ( $form['fields'] as $field ) {
    
        if ( $field->id == 24) {
             
            $inputs[] = array( 'label' => __('เลือกแผนก', 'plant'), 'id' => "24.1" );
            $inputs[] = array( 'label' => __('เลือกแพทย์', 'plant'), 'id' => "24.2" );
            $field->inputs = $inputs;
        }
    }
  
    return $form;
}
// Clinic Chained Select
add_filter( 'gform_chained_selects_input_choices_4_24_1', 'gf_populate_makes', 10, 7 );
function gf_populate_makes( array $input_choices, int $form_id, $field, int $input_id, string $chain_value, string $value, int $index ): array {
  
    $choices = array();
    $clinic = (isset($_GET['cn'])) ? sanitize_text_field($_GET['cn']) : '';
  
    $args = array(
        'post_type' => 'centers',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query( $args );

    while ( $the_query->have_posts() ) {
        $the_query->the_post();
         
        $choices[] = array(
            'text'       => get_the_title(),
            'value'      => get_the_ID(),
            'isSelected' => $clinic == get_the_ID()
        );
    }
     
    wp_reset_postdata();
  
    return $choices;
}
// Doctor Chained Select
add_filter( 'gform_chained_selects_input_choices_4_24_2', 'gf_populate_models', 10, 7 );
function gf_populate_models( array $input_choices, int $form_id, $field, int $input_id, array $chain_value, string $value, int $index ): array {
  
    $choices = array();
    $doctor = isset($_GET["dt"]) ? sanitize_text_field($_GET["dt"]) : '';
    $selected_make = $chain_value[ "{$field->id}.1" ];

    $args = array(
        'post_type' => 'doctor',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query'    => array(
            'relation'      => 'AND',
            array(
                'key'       => 'center_post',
                'value'     => $selected_make,
                'compare'   => 'LIKE',
            ),
        ),
    );
    $the_query = new WP_Query( $args );

    while ( $the_query->have_posts() ) {
        $the_query->the_post();
         
        $choices[] = array(
            'text'       => get_the_title(),
            'value'      => get_the_ID(),
            'isSelected' => $doctor == get_the_ID()
        );
    }

    wp_reset_postdata();
  
    return $choices;
}

 
//add data in form3
add_filter( 'gform_pre_render', 'add_data_form_3' );
add_filter( 'gform_pre_validation', 'add_data_form_3' );
add_filter( 'gform_admin_pre_render', 'add_data_form_3' );
add_filter( 'gform_pre_submission_filter', 'add_data_form_3' );
function add_data_form_3( array $form ): array {
  
    if ( $form['id'] != '3' ) {
       return $form;
    }

    $posts = get_posts(array('post_type' => 'centers','posts_per_page' => -1));

    $items = array();

    $fields = $form['fields'];
    foreach( $form['fields'] as &$field ) {
      if ( $field->id == 3 ) {
        $field->placeholder = 'เลือกแผนก';
      }
    }

    foreach ( $posts as $post ) {
        $items[] = array( 'value' => $post->post_title, 'text' => $post->post_title );
    }

    foreach ( $form['fields'] as &$field ) {
        if ( $field->id == 3 ) {
            $field->choices = $items;
        }
    }

    return $form;
}

add_action( 'gform_advancedpostcreation_post_after_creation', 'update_product_information', 10, 4 );
function update_product_information( int $post_id, $feed, $entry, array $form ): void {
    if ( '6' === (string) $form['id'] ) {
        $access_token = get_post_meta( $post_id, 'access_token', true );
        if ( ! $access_token ) {
            $access_token = samui_generate_patient_access_token();
            update_post_meta( $post_id, 'access_token', $access_token );
        }
        wp_redirect( '/patients-details/?token=' . $access_token );
        exit;
    }
}

/**
 * Gravity Forms. End
 */

  

add_action('wp_head', 'connectxWebTrackin');
function connectxWebTrackin(): void {
    if ( ! is_admin() ) {
?>
<script defer type="text/javascript" id="connectxWebTracking"
src="https://bangkokhospitalsamui.com/wp-content/themes/samui/js/webTrackingSdk.min.js"></script>
<?php
}
};

/**
 * Generate a secure access token.
 */
function samui_generate_access_token(): string {
    return wp_generate_password(32, false);
}

/**
 * Generate a secure access token for patient_information posts.
 */
function samui_generate_patient_access_token(): string {
    return samui_generate_access_token();
}

/**
 * Get payment log post ID by access token.
 */
function samui_get_payment_log_by_token(string $token): int {
    $posts = get_posts(array(
        'post_type' => 'payment_logs',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'access_token',
                'value' => $token,
            ),
        ),
    ));

    return $posts ? (int) $posts[0] : 0;
}

/**
 * Get patient_information post ID by access token.
 */
function samui_get_patient_by_token(string $token): int {
    $posts = get_posts(array(
        'post_type' => 'patient_information',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'access_token',
                'value' => $token,
            ),
        ),
    ));

    return $posts ? (int) $posts[0] : 0;
}

/**
 * Verify payment callback nonce.
 */
function samui_verify_payment_callback_nonce(string $nonce): bool {
    return wp_verify_nonce($nonce, 'payment_callback');
}

/**
 * Backfill access tokens for existing payment_logs posts.
 */
function samui_backfill_payment_log_tokens(): void {
    $posts = get_posts(array(
        'post_type' => 'payment_logs',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'access_token',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key' => 'access_token',
                'value' => '',
                'compare' => '=',
            ),
        ),
    ));

    foreach ($posts as $post_id) {
        update_post_meta($post_id, 'access_token', samui_generate_access_token());
    }
}

/**
 * Backfill access tokens for existing patient_information posts.
 */
function samui_backfill_patient_tokens(): void {
    $posts = get_posts(array(
        'post_type' => 'patient_information',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'access_token',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key' => 'access_token',
                'value' => '',
                'compare' => '=',
            ),
        ),
    ));

    foreach ($posts as $post_id) {
        update_post_meta($post_id, 'access_token', samui_generate_patient_access_token());
    }
}

/**
 * Generate callback token for new payment_logs posts.
 */
add_action('gform_advancedpostcreation_post_after_creation', 'samui_add_payment_log_tokens', 10, 4);
function samui_add_payment_log_tokens(int $post_id, $feed, $entry, array $form): void {
    if (isset($feed['post_type']) && $feed['post_type'] === 'payment_logs') {
        // Set entry_id from the actual GF entry ID (safety net for feed mapping issues)
        $existing_entry_id = get_post_meta($post_id, 'entry_id', true);
        if (!$existing_entry_id && isset($entry['id'])) {
            update_post_meta($post_id, 'entry_id', (int) $entry['id']);
        }
        // Ensure payment_page is set correctly (safety net for feed mapping issues)
        $existing_payment_page = get_post_meta($post_id, 'payment_page', true);
        if (!$existing_payment_page || $existing_payment_page === '1') {
            $pmp = isset($_GET['pmp']) ? intval($_GET['pmp']) : 2;
            $payment_page = ($pmp == 1) ? 'e-payment-package' : 'e-payment';
            update_post_meta($post_id, 'payment_page', $payment_page);
        }
        // Generate access_token if missing (needed for redirect from logid to token)
        $existing_token = get_post_meta($post_id, 'access_token', true);
        if (!$existing_token) {
            update_post_meta($post_id, 'access_token', samui_generate_access_token());
        }
        // Generate callback_token if missing
        $existing_callback = get_post_meta($post_id, 'callback_token', true);
        if (!$existing_callback) {
            update_post_meta($post_id, 'callback_token', samui_generate_access_token());
        }
    }
}

/**
 * Add access_token to patient_information posts on creation via Gravity Forms.
 */
add_action('gform_advancedpostcreation_post_after_creation', 'samui_add_patient_access_token', 9, 4);
function samui_add_patient_access_token(int $post_id, $feed, $entry, array $form): void {
    if (isset($feed['post_type']) && $feed['post_type'] === 'patient_information') {
        update_post_meta($post_id, 'access_token', samui_generate_patient_access_token());
    }
}

/**
 * Pre-fill E-Payment form 2 from patient_token query param.
 */
add_filter('gform_pre_render_2', 'samui_prefill_form2_from_patient_token');
add_filter('gform_pre_validation_2', 'samui_prefill_form2_from_patient_token');
add_filter('gform_pre_submission_filter_2', 'samui_prefill_form2_from_patient_token');
add_filter('gform_admin_pre_render_2', 'samui_prefill_form2_from_patient_token');
function samui_prefill_form2_from_patient_token(array $form): array {
    $patient_token = isset($_GET['patient_token']) ? sanitize_text_field($_GET['patient_token']) : '';
    if ($patient_token === '') {
        return $form;
    }

    $patient_id = samui_get_patient_by_token($patient_token);
    if ($patient_id === 0) {
        return $form;
    }

    $prefix = get_field('prefix', $patient_id);
    $first_name = get_field('first_name', $patient_id);
    $last_name = get_field('last_name', $patient_id);
    $hn = get_field('hn', $patient_id);

    foreach ($form['fields'] as &$field) {
        if ($field->inputName === 'hn' && $hn !== '') {
            $field->defaultValue = $hn;
        } elseif ($field->inputName === 'prefix' && $prefix !== '') {
            $field->defaultValue = $prefix;
        } elseif ($field->inputName === 'fname' && $first_name !== '') {
            $field->defaultValue = $first_name;
        } elseif ($field->inputName === 'lname' && $last_name !== '') {
            $field->defaultValue = $last_name;
        }
    }

    return $form;
}

/**
 * Add nonce-based CSP header for payment-related pages.
 */
add_action('template_redirect', 'samui_payment_csp_header');
function samui_payment_csp_header(): void {
        if ( ! is_singular() ) {
                return;
    }

    $page_template = get_page_template_slug( get_queried_object_id() );
    $allowed_templates = array(
        'templates/payment-loading.php',
        'templates/payment-callback.php',
        'templates/patients-details.php',
        'templates/print-payment.php',
    );

    if ( ! in_array( $page_template, $allowed_templates, true ) ) {
        return;
    }

    $nonce = base64_encode( random_bytes( 16 ) );
    $GLOBALS['samui_payment_csp_nonce'] = $nonce;
    if ( ! headers_sent() ) {
        header( 'Content-Security-Policy: script-src \'nonce-' . $nonce . '\' \'unsafe-inline\' \'unsafe-eval\' https://cdn.datatables.net https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://www.googletagmanager.com https://cdnjs.cloudflare.com https://app.connect-x.tech; style-src \'nonce-' . $nonce . '\' \'unsafe-inline\' https://fonts.googleapis.com https://cdn.jsdelivr.net https://app.connect-x.tech; font-src \'self\' https://fonts.gstatic.com data:; img-src \'self\' data: https:; connect-src \'self\' https://api.wordpress.org https://bangkokhospitalsamui.com https://tracking.connect-x.tech; frame-src \'self\' https://www.google.com https://www.gstatic.com; frame-ancestors \'none\'; base-uri \'self\'; form-action \'self\' https://www.krungsrieasypay.com; worker-src \'self\' blob:' );
    }
}


add_action('init', 'samui_maybe_backfill_tokens');
function samui_maybe_backfill_tokens(): void {
    if ( get_transient( 'samui_tokens_backfilled' ) ) {
        return;
    }
    samui_backfill_payment_log_tokens();
    samui_backfill_patient_tokens();
    set_transient( 'samui_tokens_backfilled', '1', DAY_IN_SECONDS );
}


add_action('gform_after_submission', 'redirect_to_easypay', 10, 2);

function redirect_to_easypay($entry, $form) {
    

   
$formId = (int) rgar($form, 'id');


if ($formId === 2) {
    // Gravity Forms ID 2
    $amount      = rgar($entry, '34');
    $name        = rgar($entry, '9');
    $email       = rgar($entry, '6');
    $productName = rgar($entry, '24');

} elseif ($formId === 1) {
    // Gravity Forms ID 1
    $amount      = rgar($entry, '16');
    $name        = rgar($entry, '6');
    $email       = rgar($entry, '9');
    $productName = rgar($entry, '15');

} else {
    return;
}

// Normalize amount: support 2,500 and 2500
$amount = trim((string) $amount);
$amount = str_replace(',', '', $amount);

// Validate amount
if (
    $amount === '' ||
    !is_numeric($amount) ||
    (float) $amount <= 0
) {
    wp_die('Invalid payment amount. Please contact the hospital.');
}

    // 🔹 generate orderRef (สำคัญ: unique)
    $orderRef = 'ORD' . time();

    // 🔹 config
    $merchantId = "950221114";
    $secretKey  = "Y0e8NseS4N7oMDDt6SNijUXZnCVXwBI5";

    $currCode = "764";
    $payType  = "N";

    // 🔐 generate hash
    $hashString = $merchantId . "|" . $orderRef . "|" . $currCode . "|" . $amount . "|" . $payType . "|" . $secretKey;
    $secureHash = hash('sha256', $hashString);

    // 🔗 URLs ของคุณ
    $successUrl = "https://bangkokhospitalsamui.com/en/successurl/?ref=".$orderRef;
    $failUrl    = "https://bangkokhospitalsamui.com/en/failurl/";
    $cancelUrl  = "https://bangkokhospitalsamui.com/en/cancelurl/";

    // 🔁 redirect ไป EASYPay
    echo '<form id="easypay" method="post" action="https://www.krungsrieasypay.com/BAY/eng/payment/payForm.jsp">

        <input type="hidden" name="merchantId" value="'.$merchantId.'">
        <input type="hidden" name="orderRef" value="'.$orderRef.'">
        <input type="hidden" name="amount" value="'.$amount.'">
        <input type="hidden" name="currCode" value="'.$currCode.'">
        <input type="hidden" name="remark" value="'.esc_attr($productName).'">
        <input type="hidden" name="lang" value="E">

        <input type="hidden" name="successUrl" value="'.$successUrl.'">
        <input type="hidden" name="failUrl" value="'.$failUrl.'">
        <input type="hidden" name="cancelUrl" value="'.$cancelUrl.'">

        <input type="hidden" name="payType" value="'.$payType.'">
        <input type="hidden" name="secureHash" value="'.$secureHash.'">

    </form>

    <script>document.getElementById("easypay").submit();</script>';

    exit;
}

/**
 * Krungsri EASYPay result pages
 * Shortcode: [easypay_result]
 *
 * These pages display the payment return state only.
 * They do NOT confirm that a payment has been settled.
 */

 add_shortcode('easypay_result', 'samui_easypay_result_shortcode');

 function samui_easypay_result_shortcode() {
     $page_slug = get_post_field('post_name', get_queried_object_id());
 
     $states = array(
         'successurl' => array(
             'title'   => 'Payment Received',
             'message' => 'Thank you. We have received your return from the payment page and are checking your transaction.',
             'status'  => 'Verification in progress',
             'class'   => 'pending',
         ),
         'cancelurl' => array(
             'title'   => 'Payment Cancelled',
             'message' => 'Your payment process was cancelled. If you did not intend to cancel, you may return and try again.',
             'status'  => 'Cancelled by user',
             'class'   => 'cancelled',
         ),
         'failurl' => array(
             'title'   => 'Payment Unsuccessful',
             'message' => 'We could not confirm a successful payment from this return. Please check with your bank before attempting another payment.',
             'status'  => 'Not confirmed',
             'class'   => 'failed',
         ),
     );
 
     if (!isset($states[$page_slug])) {
         return '<p>Payment result page not found.</p>';
     }
 
     $state = $states[$page_slug];
 
     $reference = isset($_GET['ref']) && is_string($_GET['ref'])
         ? sanitize_text_field(wp_unslash($_GET['ref']))
         : '';
 
     ob_start();
     ?>
     <section class="samui-payment-result">
         <div class="samui-payment-card">
             <div class="samui-payment-mark <?php echo esc_attr($state['class']); ?>">
                 <?php
                 if ($state['class'] === 'pending') {
                     echo esc_html('…');
                 } elseif ($state['class'] === 'cancelled') {
                     echo esc_html('–');
                 } else {
                     echo esc_html('!');
                 }
                 ?>
             </div>
 
             <p class="samui-payment-eyebrow">ONLINE PAYMENT</p>
 
             <h1><?php echo esc_html($state['title']); ?></h1>
 
             <p class="samui-payment-message">
                 <?php echo esc_html($state['message']); ?>
             </p>
 
             <div class="samui-payment-status">
                 <span>Current status</span>
                 <strong><?php echo esc_html($state['status']); ?></strong>
             </div>
 
             <?php if ($reference !== '') : ?>
                 <div class="samui-payment-reference">
                     <span>Reference number</span>
                     <strong><?php echo esc_html($reference); ?></strong>
                 </div>
             <?php endif; ?>
 
             <p class="samui-payment-note">
                 Please keep your payment reference for your records.
                 If the amount has been charged to your card, please contact
                 the hospital before making another payment.
             </p>
 
             <a class="samui-payment-button"
                href="<?php echo esc_url(home_url('/')); ?>">
                 Return to Website
             </a>
 
             <p class="samui-payment-support">
                 Need assistance? Please contact the hospital's payment team.
             </p>
         </div>
     </section>
 
     <style>
         .samui-payment-result {
             box-sizing: border-box;
             padding: 48px 20px;
             background: #f5f7fa;
             color: #253449;
             font-family: inherit;
         }
         .samui-payment-card {
             box-sizing: border-box;
             max-width: 640px;
             margin: 0 auto;
             padding: 44px 38px;
             background: #fff;
             border: 1px solid #e4e9ef;
             border-radius: 14px;
             text-align: center;
             box-shadow: 0 8px 30px rgba(24, 45, 70, .06);
         }
         .samui-payment-mark {
             display: flex;
             align-items: center;
             justify-content: center;
             width: 58px;
             height: 58px;
             margin: 0 auto 22px;
             border: 2px solid currentColor;
             border-radius: 50%;
             font-size: 30px;
             font-weight: 600;
         }
         .samui-payment-mark.pending { color: #9a6b13; }
         .samui-payment-mark.cancelled { color: #64748b; }
         .samui-payment-mark.failed { color: #b42318; }
         .samui-payment-eyebrow {
             margin: 0 0 10px;
             color: #64748b;
             font-size: 12px;
             font-weight: 700;
             letter-spacing: 2px;
         }
         .samui-payment-card h1 {
             margin: 0 0 16px;
             color: #17365d;
             font-size: clamp(26px, 4vw, 34px);
             line-height: 1.25;
         }
         .samui-payment-message {
             margin: 0 auto 26px;
             max-width: 490px;
             color: #526174;
             font-size: 16px;
             line-height: 1.75;
         }
         .samui-payment-status,
         .samui-payment-reference {
             display: flex;
             flex-wrap: wrap;
             justify-content: space-between;
             gap: 10px;
             padding: 15px 0;
             border-top: 1px solid #e8edf2;
             text-align: left;
             font-size: 14px;
         }
         .samui-payment-status span,
         .samui-payment-reference span {
             color: #64748b;
         }
         .samui-payment-status strong,
         .samui-payment-reference strong {
             overflow-wrap: anywhere;
             color: #253449;
         }
         .samui-payment-note {
             margin: 22px 0;
             padding: 14px 16px;
             background: #f7f9fc;
             border-radius: 8px;
             color: #526174;
             font-size: 13px;
             line-height: 1.7;
             text-align: left;
         }
         .samui-payment-button {
             display: inline-block;
             padding: 13px 24px;
             background: #17365d;
             border-radius: 7px;
             color: #fff !important;
             font-size: 15px;
             font-weight: 600;
             text-decoration: none !important;
         }
         .samui-payment-button:hover {
             background: #244c7b;
         }
         .samui-payment-support {
             margin: 22px 0 0;
             color: #64748b;
             font-size: 13px;
             line-height: 1.6;
         }
         @media (max-width: 480px) {
             .samui-payment-result { padding: 24px 12px; }
             .samui-payment-card { padding: 30px 20px; }
         }
     </style>
     <?php
 
     return ob_get_clean();
 }
 