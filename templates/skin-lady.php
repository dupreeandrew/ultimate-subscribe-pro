<?php

/*
Variables $title, $body, and $html_buttons come from skin-variables.php.
These variables have been safely escaped from that file.
*/

wp_enqueue_style('usp-form-skin-lady',USP_BASE_URL . "css/form-skin-lady.css");
wp_enqueue_script('usp-form-skin-lady-js', USP_BASE_URL . "js/form-skin-lady.js", ["jquery"]);
include(USP_BASE_DIR . 'templates/skin-variables.php');

?>

<!-- Thanks ColorLib for producing an awesome HTML/CSS/js template under CC BY 3.0! -->
<!-- https://colorlib.com/etc/regform/colorlib-regform-20/ -->
<!-- This template has been edited to match product standards -->
<!-- No visible attribution required, only an HTML comment as per support email -->

<div id="usp-skin-lady-wrapper">
    <div class="usp-lady-wrapper">
        <div class="usp-lady-inner">
            <div class="usp-lady-image-holder">
                <img src="<?php echo USP_BASE_URL . "assets/registration-form-4.jpg";?>" alt="">
            </div>
            <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" class="usp-form-js"<?php if (isset($fake_form) && ($fake_form === true)) echo ' onsubmit="return false"'; ?>>
                <h3 class="usp-form-title-text"><?php echo esc_html($title); ?></h3>
                <p class="usp-form-body-text"><?php echo esc_html($body); ?></p>
                <div class="usp-lady-form-holder usp-lady-active">
                    <input type="text" name="usp_name" placeholder="<?php esc_html_e('name', 'ultimate-subscribe-pro'); ?>" class="usp-lady-form-control">
                </div>
                <div class="usp-lady-form-holder">
                    <input type="email" name="usp_email" placeholder="<?php esc_html_e('e-mail', 'ultimate-subscribe-pro'); ?>" class="usp-lady-form-control" required>
                </div>

				<?php if ($fake_form === false) { wp_nonce_field( 'usp_submit_new_subscriber_nonce', 'usp_nonce' ); } ?>

                <div class="usp-lady-form-login usp-form-submit">
                    <?php echo usp_sanitize_html_button_payload($html_buttons); ?>
                </div>

                <p style="display: none; margin-top: 8px;" class="usp-submission-response"></p>

            </form>
        </div>
    </div>
</div>
<!-- This templates was made by Colorlib (https://colorlib.com) -->