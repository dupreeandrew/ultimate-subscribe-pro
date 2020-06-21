<?php

/*
Variables $title, $body, and $html_buttons come from skin-variables.php.
These variables have been safely escaped from that file.
*/

wp_enqueue_style('usp-form-skin-basic', USP_BASE_URL . "css/form-skin-basic.css");
wp_enqueue_style('usp-material-textbox', USP_BASE_URL . "css/material-textbox.css");

include(USP_BASE_DIR . 'templates/skin-variables.php');

?>


<div id="usp-skin-basic-wrapper">

    <div id="usp-skin-basic-bluebar">
        <img src="<?php echo USP_BASE_URL . "assets/mail-128x128.png"; ?>" alt="mail icon">
    </div>

    <div id="usp-skin-basic-content">

        <h1 id="usp-form-title" class="usp-form-title-text"><?php echo esc_html($title); ?></h1>

        <p id="usp-form-basic-body" class="usp-form-body-text">
            <?php echo esc_html($body); ?>
        </p>

        <form class="usp-form-js" id="usp-skin-basic-form" action="<?php echo admin_url('admin-ajax.php');?>" method="post"<?php if (isset($fake_form) && ($fake_form === true)) echo ' onsubmit="return false"'; ?>>

			<?php if ($fake_form === false) { wp_nonce_field( 'usp_submit_new_subscriber_nonce', 'usp_nonce' ); } ?>

            <div class="usp-input-group">
                <input id="usp_email" name="usp_email" type="email" placeholder=" " required>
                <span class="usp-input-highlight"></span>
                <span class="usp-bar"></span>
                <label for="usp_email"><?php esc_html_e('Email address', 'ultimate-subscribe-pro'); ?></label>
            </div>

            <div class="usp-input-group">
                <input id="usp_basic_name" name="usp_name" type="text" placeholder=" " required>
                <span class="usp-highlight"></span>
                <span class="usp-bar"></span>
                <label for="usp_basic_name"><?php esc_html_e('Name', 'ultimate-subscribe-pro'); ?></label>
            </div>


            <p class="usp-form-submit">
                <?php echo usp_sanitize_html_button_payload($html_buttons); ?>
            </p>

            <p style="display: none; margin-top: 8px;" class="usp-submission-response"></p>

        </form>

    </div>
</div>