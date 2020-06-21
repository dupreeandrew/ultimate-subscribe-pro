<?php

/*
Variables $title, $body, and $html_buttons come from skin-variables.php.
These variables have been safely escaped from that file.
*/

wp_enqueue_style('usp-form-skin-smooth', USP_BASE_URL . "css/form-skin-smooth.css");
wp_enqueue_script('usp-form-skin-smooth-js', USP_BASE_URL . "js/form-skin-smooth.js", ["jquery"]);
include(USP_BASE_DIR . 'templates/skin-variables.php');

?>

<!-- Thanks ColorLib for producing an awesome HTML/CSS/js template under CC BY 3.0! -->
<!-- https://colorlib.com/wp/template/login-form-v2/ -->
<!-- No visible attribution required, as per support email -->
<!-- This template has been edited to match product standards -->

<div id="usp-skin-smooth-wrapper">
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form action="<?php echo admin_url('admin-ajax.php');?>" class="login100-form validate-form usp-form-js" method="post"<?php if (isset($fake_form) && ($fake_form === true)) echo ' onsubmit="return false"'; ?>>
                    <span class="login100-form-title p-b-26 usp-form-title-text">
                        <?php echo esc_html($title); ?>
                    </span>
                    <span class="login100-form-title p-b-48">
                        <p class="usp-form-body-text"><?php echo esc_html($body); ?></p>
                    </span>

                    <div class="wrap-input100 validate-input" data-validate = "<?php esc_html_e('Please enter a valid email', 'ultimate-subscribe-pro'); ?>">
                        <input class="input100" style="box-shadow: none" type="email" name="usp_email" required>
                        <span class="focus-input100" data-placeholder="<?php esc_html_e('Your email', 'ultimate-subscribe-pro'); ?>"></span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="text" name="usp_name" style="box-shadow: none" required>
                        <span class="focus-input100" data-placeholder="<?php esc_html_e('Your name', 'ultimate-subscribe-pro'); ?>"></span>
                    </div>

					<?php if ($fake_form === false) { wp_nonce_field( 'usp_submit_new_subscriber_nonce', 'usp_nonce' ); } ?>

                    <div id="container-login100-form-btn">
                        <div class="usp-form-submit" id="wrap-login100-form-btn">
                            <?php echo usp_sanitize_html_button_payload($html_buttons); ?>
                        </div>
                    </div>

                    <p style="display: none; margin-top: 8px;" class="usp-submission-response"></p>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- This templates was made by Colorlib (https://colorlib.com) -->