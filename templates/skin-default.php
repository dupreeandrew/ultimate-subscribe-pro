<?php

/*
Variables $title, $body, and $html_buttons come from skin-variables.php.
These variables have been safely escaped from that file.
*/

wp_enqueue_style('usp-form-skin-default', USP_BASE_URL . "css/form-skin-default.css");
include(USP_BASE_DIR . 'templates/skin-variables.php');

?>

<div id="usp-skin-default-wrapper">

    <img src="<?php echo USP_BASE_URL . 'assets/mailbox-514x602.png'; ?>">

    <h1 id="usp-skin-default-header" class="usp-form-title-text"><?php echo esc_html($title); ?></h1>

    <p id="usp-skin-default-body" class="usp-form-body-text">
        <?php echo esc_html($body); ?>
    </p>

    <form class="usp-form-js" id="usp-skin-default-form" action="<?php echo admin_url('admin-ajax.php');?>" method="post"<?php if (isset($fake_form) && ($fake_form === true)) echo ' onsubmit="return false"'; ?>>

		<?php if ($fake_form === false) { wp_nonce_field( 'usp_submit_new_subscriber_nonce', 'usp_nonce' ); } ?>

        <p>
            <input id="usp_default_email" name="usp_email" type="email" placeholder="<?php esc_html_e('Enter your email', 'ultimate-subscribe-pro'); ?>" required>
        </p>
        <p>
            <input id="usp_default_name" name="usp_name" type="text" placeholder="<?php esc_html_e('Enter your name', 'ultimate-subscribe-pro'); ?>" required>
        </p>

        <p class="usp-form-submit">
			<?php echo usp_sanitize_html_button_payload($html_buttons); ?>
        </p>

        <p style="display: none; margin-top: 8px;" class="usp-submission-response"></p>

    </form>

</div>