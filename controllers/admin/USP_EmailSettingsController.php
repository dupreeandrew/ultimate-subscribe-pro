<?php


class USP_EmailSettingsController implements USP_Controller {

	public const DEFAULT_EMPTY_PASSWORD = "        ";

	public function init() {
		$email_settings_title = esc_html__("Email Settings", 'ultimate-subscribe-pro');
		add_submenu_page("subscribepro",
			$email_settings_title,
			$email_settings_title,
			"manage_options",
			"subscribepro_email_settings",
			[$this, 'load_page']);
	}

	public function load_page() {

		require(USP_BASE_DIR . "models/USP_EmailSettings.php");

		$this->check_page_save();
		require_once(USP_BASE_DIR . "views/admin/USP_EmailSettingsView.php");
		$email_settings = USP_EmailSettings::get_email_settings();
		USP_EmailSettingsView::renderHTML($email_settings);
		return;
	}

	private function check_page_save() {

		if ($_SERVER['REQUEST_METHOD'] !== "POST") {
			return;
		}

		$email_settings_test_msg = esc_html__("Testing email settings..", "ultimate-subscribe-pro");
		USP_Announcer::echoNotification($email_settings_test_msg);
		ob_flush();
		flush();

		$email_settings = $_POST['usp_email_settings'];
		$email = sanitize_email($email_settings['email']);
		$smtp_profile = sanitize_text_field($email_settings['profile']);
		$smtp_server = sanitize_text_field($email_settings['server']);
		$smtp_password = $this->get_actual_smtp_password($email_settings['password']);
		$smtp_port = (int)$email_settings['port'];
		$smtp_encryption = sanitize_text_field($email_settings['encryption']);
		$use_confirmation_email = isset($email_settings['use_confirmation']);
		$confirmation_subject = sanitize_text_field($email_settings['confirmation_subject']);
		$confirmation_body = wp_kses_post(stripslashes($_POST['usp_email_settings_confirmation_body']));

		$email_settings = new USP_EmailSettings();
		$email_settings->set_email($email)
			->set_use_smtp(($smtp_profile !== "none"))
			->set_smtp_profile($smtp_profile)
			->set_smtp_server($smtp_server)
			->set_smtp_password($smtp_password)
			->set_smtp_port($smtp_port)
			->set_smtp_encryption($smtp_encryption)
			->set_use_confirmation_email($use_confirmation_email)
			->set_confirmation_subject($confirmation_subject)
			->set_confirmation_body($confirmation_body);

		// this will ensure all data is valid.
		$error_message = esc_html($email_settings->verify_properties());
		if ($error_message !== "") {
			USP_Announcer::echoNotification($error_message);
			return;
		}

		if (USP_EmailSettings::set_email_settings($email_settings)) {
			$msg = esc_html__("Your settings were saved!", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($msg, true);
		}
		else {
			$msg = esc_html__("Your settings were saved, but a test email sent to your site's admin email failed. 
			You may want to check the Help section.", "ultimate-subscribe-pro");
			USP_Announcer::echoNotification($msg, false);
		}

	}

	private function get_actual_smtp_password($entered_password) {
		// we can't really sanitize this.. but rest assured, this data IS being sent to a wordpress function.
		if ($entered_password === self::DEFAULT_EMPTY_PASSWORD) {
			return USP_EmailSettings::get_email_settings()['smtp_password'];
		}
		else {
			return $entered_password;
		}
	}

}