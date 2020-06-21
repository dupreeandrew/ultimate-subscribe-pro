<?php

/**
 * This class listens if any user activates/deletes his subscription
 * Activation URL:
 * /?action=confirm-subscriber-code&email={email}&code={unique_code}
 *
 * Deactivation URL:
 * /?action=remove-subscription&email={email}&code={unique_code}
 */
class USP_CodeListener {
	public static function start_listening() {

		if ( ! isset( $_GET['action'] ) ) {
			return;
		}

		if (!isset($_GET['code'])) {
			return;
		}

		if (!isset($_GET['email'])) {
			return;
		}

		$email = filter_input( INPUT_GET, 'email', FILTER_SANITIZE_EMAIL );
		$code = sanitize_text_field($_GET['code']);

		require(USP_BASE_DIR . "models/USP_Category.php");
		if ($_GET['action'] === "confirm-subscriber-code") {
			$result = USP_Category::confirm_activation_code(urldecode($email), $code);

			if ($result) {
				$title = esc_html__("Success!", 'ultimate-subscribe-pro');
				$body = esc_html__("You are now a registered subscriber.", 'ultimate-subscribe-pro');
			}
			else {
				$title = esc_html__("We're Sorry", 'ultimate-subscribe-pro');
				$body = esc_html__("For some reason, we were not able to add you to the subscription system. Please try again later.",
					'ultimate-subscribe-pro');
			}
		}
		else if ($_GET['action'] === "remove-subscription") {
			$result = USP_Category::remove_subscriber($email, $code);
			if ($result) {
				$title = esc_html__("Success!", 'ultimate-subscribe-pro');
				$body = esc_html__("You are no longer a registered subscriber", 'ultimate-subscribe-pro');
			}
			else {
				$title = esc_html__("We're Sorry", 'ultimate-subscribe-pro');
				$body = esc_html__("It looks you aren't a registered subscriber at the moment!", "ultimate-subscribe-pro");
			}
		}

		include(USP_BASE_DIR . "templates/html-unique-code.php");
		wp_die();


	}

	/**
	 * Returns a subscriber activation URL given an email and unique code of the user.
	 */
	public static function get_activation_url($email, $unique_code) {
		$email = urlencode($email);

		return site_url() . "/?action=confirm-subscriber-code&email=$email&code=$unique_code";
	}

	/**
	 * Returns a subscriber deactivation URL given an email and unique code of the user.
	 */
	public static function get_deactivation_url($email, $unique_code) {
		$email = urlencode($email);
		return site_url() . "/?action=remove-subscription&email=$email&code=$unique_code";
	}


}