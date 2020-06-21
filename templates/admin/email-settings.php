<?php

// probably the least clean code here, as this should be done in the Controller,
// but in exchange, we can save tons of functions & lines of code, making it easier to understand? just less organized
register_setting('subscribepro_email_settings', 'usp_email_settings');
wp_enqueue_script("usp-email-settings", USP_BASE_URL . 'js/email-settings.js', ["jquery"]);
wp_enqueue_style("usp-admin", USP_BASE_URL . "css/usp-admin.css");

?>

<div class="wrap">

    <div class="nav-tab-wrapper">
        <a class="nav-tab" href="<?php echo USP_URLGetter::getSendEmailPage(); ?>"><?php esc_html_e('Send Emails', 'ultimate-subscribe-pro'); ?></a>
        <a class="nav-tab nav-tab-active" href="#"><?php esc_html_e('Email Settings', 'ultimate-subscribe-pro'); ?></a>
    </div>

    <hr class="wp-header-end">

    <h1><?php esc_html_e('Email Settings', 'ultimate-subscribe-pro'); ?></h1>
    <form method="post" id="usp-form-email-settings">
        <h2><?php esc_html_e('SMTP Settings', 'ultimate-subscribe-pro'); ?></h2>
        <p>
			<?php esc_html_e('Here you can configure SMTP settings. SMTP allows the web server to send an email through a specified
            email provider instead of the server itself. This is helpful because this may decrease the chances of
            your email getting sent to your subscriber\'s spambox.', 'ultimate-subscribe-pro'); ?>
        </p>
        <p>
			<?php esc_html_e('If you are sending an email under an email address that ends with your website\'s domain, you might not
            need to use SMTP to prevent your email from going to spam. To find out, subscribe to one of your own
            categories, and send an email to yourself. It\'s recommended that you subscribe using a gmail account,
            since Google has fairly reasonable spam measures.', 'ultimate-subscribe-pro'); ?>
        </p>

        <p>
			<?php esc_html_e('Also, it\'s highly recommended that you use your own local mail server for SMTP! Using a service such as
            Google increases the amount of time to mail. This works, but if you are sending to over 200+ subscribers,
            the mail system may be slow. This is not a plugin defect, but rather just the way SMTP works. If you use
            your own local mail server, email times will be a lot quicker.', 'ultimate-subscribe-pro'); ?>
        </p>

        <table class="form-table">

            <tr>
                <th scope="row">
                    <?php
                    esc_html_e('SMTP Profile:', 'ultimate-subscribe-pro');
                    $smtp_profile = sanitize_text_field($email_settings['smtp_profile']);
                    ?>
                </th>
                <td>
                    <select name="usp_email_settings[profile]" id="usp-smtp-profile-js" value="<?php echo esc_html($smtp_profile); ?>">
                        <?php $profile = $email_settings['smtp_profile']; ?>
                        <option value="none"<?php if ($profile === "none") echo " selected"; ?>>None</option>
                        <option value="gmail"<?php if ($profile === "gmail") echo " selected"; ?>>Gmail</option>
                        <option value="ymail"<?php if ($profile === "ymail") echo " selected"; ?>>Ymail</option>
                        <option value="custom"<?php if ($profile === "custom") echo " selected"; ?>>Custom</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <?php esc_html_e('Sender\'s Email', 'ultimate-subscribe-pro'); ?>
                </th>
                <td>
                    <input name="usp_email_settings[email]" type="email" placeholder="account@domain.com" value="<?php echo esc_html($email_settings['email']); ?>" required>
                </td>
            </tr>

            <tbody id="usp-smtp-custom-settings">
                <tr>
                    <th scope="row">
                    <?php esc_html_e('Email Password:', 'ultimate-subscribe-pro'); ?>
                    </th>
                    <td>
                        <?php // to display actual password: echo htmlentities(stripslashes($email_settings['smtp_password'])); ?>
                    <input id="usp-smtp-password-js" name="usp_email_settings[password]" type="password" value="<?php echo USP_EmailSettingsController::DEFAULT_EMPTY_PASSWORD ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    <?php esc_html_e('SMTP Server:', 'ultimate-subscribe-pro'); ?>
                    </th>
                    <td>
                    <input id="usp-smtp-server-js" name="usp_email_settings[server]" type="text" value="<?php echo sanitize_text_field($email_settings['smtp_server']); ?>" placeholder="smtp.domain.com">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    <?php esc_html_e('SMTP Port:', 'ultimate-subscribe-pro'); ?>
                    </th>
                    <td>
                    <input id="usp-smtp-port-js" name="usp_email_settings[port]" type="text" value="<?php echo (int)$email_settings['smtp_port']; ?>" placeholder="465">
                    </td>
                </tr>

                <tr>
                    <?php $smtp_encryption = $email_settings['smtp_encryption']; ?>
                    <th scope="row">
                    <?php esc_html_e('Encryption:', 'ultimate-subscribe-pro'); ?>
                    </th>
                    <td>
                    <select id="usp-smtp-encryption-js" name="usp_email_settings[encryption]">
                        <option value="ssl"<?php if ($smtp_encryption === "ssl") echo " selected" ;?>>SSL</option>
                        <option value="tls"<?php if ($smtp_encryption === "tls") echo " selected" ;?>>TLS</option>
                        <option value="none"<?php if ($smtp_encryption === "") echo " selected" ;?>>None</option>
                    </select>
                    </td>
                </tr>

                <tr>
                    <strong><?php esc_html_e('Note:', 'ultimate-subscribe-pro'); ?></strong>
                    <?php esc_html_e('Some mail services, such as gmail and ymail limit outgoing messages to 500/day.', 'ultimate-subscribe-pro'); ?>
                    <br />
                    <?php esc_html_e('Google may also require you to enable "less secure apps" in order for this plugin to connect to the SMTP.
                    You can enable weaker apps here:', 'ultimate-subscribe-pro'); ?>
                      <a href="https://myaccount.google.com/lesssecureapps" target="_blank">https://myaccount.google.com/lesssecureapps</a>
                </tr>


            </tbody>

        </table>

        <h2><?php esc_html_e('Other Settings', 'ultimate-subscribe-pro'); ?></h2>
        <p>
            <input name="usp_email_settings[use_confirmation]" type="checkbox" value="checked"<?php if ($email_settings['use_confirmation_email']) echo " checked"; ?>>
			<?php esc_html_e('Send new subscribers a required subscription activation email', 'ultimate-subscribe-pro'); ?>
        </p>

        <p>
			<?php esc_html_e('Confirmation Subject', 'ultimate-subscribe-pro'); ?>
            <input name="usp_email_settings[confirmation_subject]" type="text" value="<?php echo sanitize_text_field($email_settings['confirmation_subject']); ?>">
        </p>

        <div>
            <?php
            esc_html_e("Confirmation Body:", "ultimate-subscribe-pro");
            echo '<br />';
            wp_editor($email_settings['confirmation_body'], 'usp_email_settings_confirmation_body');
            ?>
        </div>

        <br />

        <h3><?php esc_html_e("Available Placeholders (automagically changed):", "ultimate-subscribe-pro"); ?></h3>
        <ul>
            <li>
                <code class="usp-code">{subscriber_name}</code> -
                <?php esc_html_e("Name of the new subscriber.", "ultimate-subscribe-pro"); ?>
            </li>
            <li>
                <code class="usp-code">{activation_link}</code> -
                <?php esc_html_e("Link for the subscriber to activate his or her subscription.", "ultimate-subscribe-pro"); ?>
            </li>
        </ul>

        <?php submit_button(esc_html__("Submit Settings", 'ultimate-subscribe-pro')); ?>

    </form>
</div>
