<?php

/**
 * Class USP_EmailSettings
 * This class is responsible for configuring the email settings.
 */
class USP_EmailSettings {

	// leave fields public. this allows for json serialization.
	public $email;
	public $use_smtp = false;
	public $smtp_server;
	public $smtp_profile = "none";
	public $smtp_password;
	public $smtp_port;
	public $use_confirmation_email = false;
	public $smtp_encryption = "";
	public $confirmation_subject = "";
	public $confirmation_body = "";

	/**
	 * USP_EmailSettings constructor.
	 */
	public function __construct() {

	}

	public function set_email($email) {
		$this->email = sanitize_email($email);
		return $this;
	}

	public function set_use_smtp($use_stmp) { // bool
		if (!is_bool($use_stmp)) {
			$use_stmp = false;
		}

		$this->use_smtp = $use_stmp;
		return $this;
	}

	public function set_smtp_profile($smtp_profile) {
		$this->smtp_profile = sanitize_text_field($smtp_profile);
		return $this;
	}

	public function set_smtp_server($smtp_server) {
		$this->smtp_server = sanitize_text_field($smtp_server);
		return $this;
	}

	public function set_smtp_password($smtp_password) {
		$this->smtp_password = $smtp_password; // passwords can't be sanitized.. but this data is sent to wp options.
		return $this;
	}

	public function set_smtp_port($smtp_port) {
		if (!is_numeric($smtp_port)) {
			$smtp_port = "";
		}
		$this->smtp_port = $smtp_port;
		return $this;
	}

	public function set_smtp_encryption($encryption) {

		if ($encryption !== "tls" && $encryption !== "ssl" && $encryption !== "none") {
			$encryption = "none";
		}

		if ($encryption === "none") {
			$this->smtp_encryption = "";
		}
		else {
			$this->smtp_encryption = $encryption;
		}

		return $this;
	}

	public function set_use_confirmation_email($use_confirmation_email) {

		if (!is_bool($use_confirmation_email)) {
			$use_confirmation_email = false;
		}

		$this->use_confirmation_email = $use_confirmation_email;
		return $this;
	}

	public function set_confirmation_subject($confirmation_subject) {
		$this->confirmation_subject = sanitize_text_field($confirmation_subject);
		return $this;
	}

	public function set_confirmation_body($confirmation_body) {
		$this->confirmation_body = wp_kses_post(stripslashes($confirmation_body));
		return $this;
	}

	/**
	 * Ensures that all the properties of the object are valid.
	 * @return string error message
	 */
	public function verify_properties() {

		$email_domain_is_local_host = substr($this->email, strlen($this->email) - 9) === "localhost";
		if (!is_email($this->email) && !$email_domain_is_local_host) {
			return esc_html__("Invalid email entered", "ultimate-subscribe-pro");
		}

		if (!$this->use_smtp) {
			return ""; // empty string = OK.
		}

		if (empty($this->smtp_server)) {
			return esc_html__("SMTP Server can not be blank", "ultimate-subscribe-pro");
		}

		if (empty($this->smtp_port)) {
			return esc_html__("SMTP Port can not be blank", "ultimate-subscribe-pro");
		}

		if (!is_numeric($this->smtp_port)) {
			return esc_html__("SMTP Port must be a number", "ultimate-subscribe-pro");
		}

		if ($this->smtp_encryption !== "ssl" && $this->smtp_encryption !== "tls" && $this->smtp_encryption !== "none" && $this->smtp_encryption !== "") {
			return "Improper smtp encryption.";
		}

		if (strlen($this->confirmation_subject) < 3) {
			return "Confirmation Subject is too short.";
		}

		return "";


	}

	/**
	 * Sets the EmailSettings object as the email settings into the database.
	 */
	public static function set_email_settings($usp_email_settings) {

		self::check_default_settings();

		$error = $usp_email_settings->verify_properties();
		if ($error !== "") {
			return false;
		}

		update_option("usp-email-settings", serialize(json_decode(json_encode($usp_email_settings), true)));

		return self::send_test_email();

	}

	/**
	 * Checks if user has default settings (or any settings for that matter).
	 * If not, it sets them.
	 */
	private static function check_default_settings() {
		if (get_option("usp-email-settings") === true) {
			return;
		}

		// Localization is not used here because it's expected that the client changes these values.
		$default_confirmation_title = "Thanks for subscribing!";
		$default_confirmation_body = "<h2>Thanks {subscriber_name} for subscribing!</h2><p>To officially be sent emails, <a href=\"{activation_link}\" target=\"_blank\">please confirm your subscription here.</p>";

		$email_settings = new USP_EmailSettings();
		$email_settings->set_email("admin@" . $_SERVER['SERVER_NAME'])
			->set_use_smtp(false)
			->set_smtp_profile("none")
			->set_smtp_password("1")
			->set_smtp_server("")
			->set_smtp_port("")
			->set_smtp_encryption("")
			->set_use_confirmation_email(false)
			->set_confirmation_subject($default_confirmation_title)
			->set_confirmation_body($default_confirmation_body);
		$email_settings_array = serialize(json_decode(json_encode($email_settings), true));
		add_option("usp-email-settings", $email_settings_array);

	}

	/**
	 * Sends a test email to see if email settings are configured correctly.
	 * @return boolean success/fail
	 */
	private static function send_test_email() {
		$admin_email = get_option('admin_email');
		$php_mailer = self::get_phpmailer();
		$php_mailer->Subject = esc_html__("USP Test Email", 'ultimate-subscribe-pro');
		$php_mailer->Body = esc_html__(
			"This is a test email sent through your WordPress Plugin UltimateSubscribePro. If you are reading this
			message, your website was successfully able to use the email settings you have set. Congratulations!",
			'ultimate-subscribe-pro');
		$php_mailer->addAddress($admin_email);
		try {
			return $php_mailer->send();
		} catch (phpmailerException $e) {
			return false;
		}
	}

	/**
	 * Returns an array of email settings
	 */
	public static function get_email_settings() {
		self::check_default_settings();

		$email_settings = unserialize(get_option("usp-email-settings"));
		foreach ($email_settings as $email_setting_key => $value) {
			if ($email_setting_key === "confirmation_body") {
				$email_settings[$email_setting_key] = wp_kses_post($value);
			}
			else {
				$email_settings[$email_setting_key] = htmlentities(stripslashes($value));
			}
		}

		return $email_settings;

	}

	/**
	 * Returns a new SMTP-configured PHP Mailer object based on the current email settings.
	 * Additional information, such as subject & body & recipients should still be set.
	 */
	public static function get_phpmailer() {

		$email_settings = self::get_email_settings();
		$site_name = get_option('blogname');


		global $phpmailer;
		if ( ! ( $phpmailer instanceof PHPMailer ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			require_once ABSPATH . WPINC . '/class-smtp.php';
		}

		$php_mailer = new PHPMailer;
		$php_mailer->setFrom($email_settings['email'], $site_name);
		$php_mailer->isHTML(true);

		if ($email_settings['use_smtp']) {
			// $email_settings is obtained from self::get_email_settings() (check above), which sanitizes data
			$php_mailer->isSMTP();
			$php_mailer->SMTPAuth = true;
			$php_mailer->SMTPSecure = $email_settings['smtp_encryption'];
			$php_mailer->Host = $email_settings['smtp_server'];
			$php_mailer->Port = $email_settings['smtp_port'];
			$php_mailer->Username = $email_settings['email'];
			$php_mailer->Password = $email_settings['smtp_password'];
		}

		return $php_mailer;

	}




}