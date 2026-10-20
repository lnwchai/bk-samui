<?php

/**
 * Template Name: API successurl
 */

?>
<?php get_header(); ?>

<main id="main" class="site-main page-main">


<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        if (!get_field('hide_title')) {
            echo plant_page_title();
        }

        echo '<div class="page-content">';
        the_content();

        $orderRef = '';
        if (isset($_GET['ref']) && is_string($_GET['ref'])) {
            $orderRef = sanitize_text_field(wp_unslash($_GET['ref']));
        }
        ?>

        <section class="easypay-processing">
            <div class="easypay-processing__icon" aria-hidden="true">
                <span class="easypay-processing__spinner"></span>
            </div>

            <h2>Payment Processing...</h2>

            <p>
                Thank you. We are checking your payment transaction.
                Please wait while your transaction is being verified.
            </p>

            <?php if ($orderRef !== '') : ?>
                <div class="easypay-processing__reference">
                    <span>Reference Number</span>
                    <strong><?php echo esc_html($orderRef); ?></strong>
                </div>
            <?php endif; ?>

            <p class="easypay-processing__notice">
                Your payment has not yet been confirmed.
                Please do not make another payment until you have checked
                the status of this transaction.
            </p>
        </section>

        <?php
        edit_post_link('EDIT', '', '', null, 'btn-edit');

        echo '</div>';
    }
}
?>

      

</main>
<?php get_footer(); ?>