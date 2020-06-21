<?php


class USP_SendEmailController implements USP_Controller {

	private $deactivation_url;

	public function __construct() {
		$this->deactivation_url = site_url() . "/?action=remove-subscription";
	}

	public function init() {
		$send_emails_title = esc_html__("Send Emails", 'ultimate-subscribe-pro');
		add_submenu_page("subscribepro",
			$send_emails_title,
			$send_emails_title,
			"manage_options",
			"subscribepro_send_emails",
			[$this, 'load_page']);
	}

	public function load_page() {

		require_once(USP_BASE_DIR . "models/USP_Category.php");

		$this->check_send_mail();

		$category_list = USP_Category::getAllCategories();

		if (count($category_list) === 0) {
			$message = esc_html__("Please make a category first before using this page.", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($message);
		}

		require_once(USP_BASE_DIR . "views/admin/USP_SendEmailView.php");
		USP_SendEmailView::renderHTML($category_list);
	}

	public function check_send_mail() {

		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return;
		}

		$message = esc_html__("Your message is being sent. You may close out of this tab or navigate away.", "ultimate-subscribe-pro");
		USP_Announcer::echoNotification($message);
		ob_flush();
		flush();


		$category_id = (int)$_POST['usp_category_send'];

		$category = USP_Category::getCategory($category_id);
		if ($category === null) {
			$message = esc_html__("Category ID not found. Please contact developer if you see this message", "ultimate-subscribe-pro");
			USP_Announcer::echoNotification($message);
			return;
		}

		$subject = stripslashes($_POST['usp_email_subject']);
		$body = stripslashes($_POST['usp_email_body']); // html is needed, because it's an email.

		require_once(USP_BASE_DIR . "models/USP_EmailSettings.php");
		$phpmailer = USP_EmailSettings::get_phpmailer();

		$total_emails_sent = 0;
		$email_sent_success = true;
		$category->get_subscribers(function($name, $email, $unique_code)
			use (&$phpmailer, &$address_count, &$email_sent_success, &$total_emails_sent, &$body, &$subject) {

			$phpmailer->addAddress($email, $name);
			$phpmailer->Body = $this->replace_placeholders($body, $email, $name, $unique_code);
			$phpmailer->Subject = $this->replace_placeholders($subject, $email, $name, $unique_code);
			$email_sent_success = $phpmailer->send();
			$phpmailer->clearAllRecipients();

			$total_emails_sent++;
		});

		if ($email_sent_success) {
			$success_message = sprintf(esc_html__('Message was sent to %d subscribers!', 'ultimate-subscribe-pro'), $total_emails_sent);
			USP_Announcer::echoNotification($success_message, true);
		} else {
			$fail_message = esc_html__("Some/all emails failed to send. You may want to check SMTP settings.", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($fail_message);
		}

	}

	private function replace_placeholders($body, $email, $name, $unique_code) {
		$deactivation_url = $this->deactivation_url . "&email=" . urlencode($email) . "&code=$unique_code";
		$body = str_replace("http://{deactivation_link}", $deactivation_url, $body);
		$body = str_replace("{deactivation_link}", $deactivation_url, $body);
		$body = str_replace("{subscriber_name}", $name, $body);
		return $body;
	}

}