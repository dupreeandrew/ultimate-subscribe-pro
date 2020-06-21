<?php

/*
Variables $title, $body, and $html_buttons come from skin-variables.php.
These variables have been safely escaped from that file.
*/

wp_enqueue_style('usp-form-skin-blue', USP_BASE_URL . "css/form-skin-blue.css");
include(USP_BASE_DIR . 'templates/skin-variables.php');

?>

<!-- Thanks ColorLib for producing an awesome HTML/CSS/js template under CC BY 3.0! -->
<!-- https://colorlib.com/wp/template/login-form-v2/ -->
<!-- No visible attribution required, as per support email -->
<!-- This template has been edited to match product standards -->
<div id="usp-skin-blue-outer-wrapper">
    <div id="usp-skin-blue-wrapper">
        <div class="usp-blue-inner">
            <form class="usp-form-js" action="<?php echo admin_url('admin-ajax.php');?>" <?php if (isset($fake_form) && ($fake_form === true)) echo ' onsubmit="return false"'; ?>>
                <h3 class="usp-form-title-text"><?php echo esc_html($title); ?></h3>
                <p class="usp-form-body-text"><?php echo esc_html($body); ?></p>
                <label class="usp-blue-form-group">
                    <input type="text" name="usp_name" class="usp-blue-form-control" placeholder=" ">
                    <span><?php esc_html_e('Your name', 'ultimate-subscribe-pro'); ?></span>
                    <span class="sp-blue-border"></span>
                </label>
                <label class="usp-blue-form-group">
                    <input type="email" name="usp_email" class="usp-blue-form-control" placeholder=" " required>
                    <span><?php esc_html_e('Your email address', 'ultimate-subscribe-pro'); ?></span>
                    <span class="sp-blue-border"></span>
                </label>

				<?php if ($fake_form === false) { wp_nonce_field( 'usp_submit_new_subscriber_nonce', 'usp_nonce' ); } ?>

                <div class="usp-form-submit">
                    <?php echo usp_sanitize_html_button_payload($html_buttons); ?>
                </div>

                <p style="display: none; margin-top: 25px; margin-bottom: 0px" class="usp-submission-response"></p>

            </form>
        </div>
    </div>
</div>