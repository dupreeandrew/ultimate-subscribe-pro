<?php
/*
 * Received Variables:
 * $category_list -> array of categories, containing name & id
 */

wp_enqueue_style("usp-admin", USP_BASE_URL . "css/usp-admin.css");

?>

<div class="wrap">

	<div class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active" href="#"><?php esc_html_e('Send Emails', 'ultimate-subscribe-pro'); ?></a>
		<a class="nav-tab" href="<?php echo USP_URLGetter::getEmailSettingsPage(); ?>"><?php esc_html_e('Email Settings', 'ultimate-subscribe-pro'); ?></a>
	</div>

	<hr class="wp-header-end">

	<h1 class="wp-heading-inline"><?php esc_html_e('Email Sender', 'ultimate-subscribe-pro'); ?></h1>

    <p><?php esc_html_e('Remember, emails may end up in spam or no where at all depending on your server environment & SMTP settings.', 'ultimate-subscribe-pro'); ?></p>

    <form method="post"<?php if (count($category_list) === 0) echo " onsubmit=\"return false\""?>>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="usp_category_send"><?php esc_html_e('Category Recipient:', 'ultimate-subscribe-pro'); ?></label>
                </th>
                <td>
                    <select name="usp_category_send">
                        <?php
                        foreach ($category_list as $category) {
                            $category_id = (int)$category->getId();
                            $name = sanitize_text_field($category->getName());
                            echo "<option value='$category_id'>$name</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="usp_email_subject"><?php esc_html_e('Email Subject:', 'ultimate-subscribe-pro'); ?></label>
                </th>
                <td>
                    <input name="usp_email_subject" type="text" required>
                </td>
            </tr>

        </table>

        <?php wp_editor('', 'usp_email_body'); ?>

        <div>
            <h3><?php esc_html_e('Available Placeholders (automagically changed):', 'ultimate-subscribe-pro'); ?></h3>
            <ul>
                <li>
                    <code class="usp-code">{subscriber_name}</code> -
					<?php esc_html_e('The name of the registered subscriber.', 'ultimate-subscribe-pro'); ?>
                </li>
                <li>
                    <code class="usp-code">{deactivation_link}</code> -
					<?php esc_html_e('The link users can click to unsubscribe from emails.', 'ultimate-subscribe-pro'); ?>
                </li>
            </ul>

        </div>


		<?php
        if (count($category_list) !== 0) {
            submit_button(esc_html__("Send Emails", 'ultimate-subscribe-pro'));
        }
		?>

    </form>


</div>