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
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h1 class="widget-title">',
        'after_title'   => '</h1>',
    ),
    );
    register_sidebar(array(
        'name'          => esc_html__('Header Top Right', 'plant'),
        'id'            => 'header_top_right',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
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
        $external_url  = home_url().'/patients-details?pid='.$pid;
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
  
    if ( $form['id'] != 3 ) {
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
    if( $form['id'] == '6' ){
        wp_redirect( '/patients-details/?pid='.$post_id );
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


