<?php

/**
 * This class handles any ajax submissions through a subscription form
 */
class USP_AjaxHandler {

	public static function init() {
		add_action("wp_ajax_usp_submit_new_subscriber", 'USP_AjaxHandler::register_subscriber');
		add_action("wp_ajax_nopriv_usp_submit_new_subscriber", "USP_AjaxHandler::register_subscriber");
	}

	/**
	 * Registers a subscriber to the database using $_POST[] data.
	 * This is to be used with AJAX. This will print out the result.
	 */
	public static function register_subscriber() {

		$nonce = $_POST['usp_nonce'];
		if (!wp_verify_nonce($nonce, "usp_submit_new_subscriber_nonce")) {
			wp_die(esc_html__("You have been registered!", 'ultimate-subscribe-pro'));
		}

		if (!isset($_POST['usp_name']) || empty($_POST['usp_name'])) {
			$name = esc_html__("Subscriber", 'ultimate-subscribe-pro');
		}
		else {
			$name = sanitize_text_field($_POST['usp_name']);
		}

		if (!isset($_POST['usp_email']) || empty($_POST['usp_email'])) {
			wp_die(esc_html__("Please fill in an email!", "ultimate-subscribe-pro"));
		}

		$categoryId = (int)$_POST['category_id']; // safe
		$email = sanitize_email($_POST['usp_email']);
		$form_id = (int)$_POST['form_id'];

		require_once(USP_BASE_DIR . "models/USP_Category.php");

		$category = USP_Category::getCategory($categoryId);

		if ($category === null) {
			wp_die(esc_html__("This subscription category is currently down. Please contact a site administrator.", 'ultimate-subscribe-pro'));
		}

		$response_code = $category->add_subscriber($name, $email);
		if ($response_code === USP_Category::ADD_SUBSCRIBER_SUCCESS) {
			self::dont_show_popup_for_a_day($form_id);
			wp_die(esc_html__("You have been registered!", 'ultimate-subscribe-pro'));
		}
		else if ($response_code === USP_Category::ADD_SUBSCRIBER_NEEDS_CONFIRMATION_EMAIL) {
			self::dont_show_popup_for_a_day($form_id);

			ob_end_clean(); // thanks wordpress
			ob_start();
			esc_html_e("You have been registered! An activation email will be sent to you soon.", 'ultimate-subscribe-pro');
			$size = ob_get_length();
			header("Content-Length: $size");
			header('Connection: close');
			header("Content-Encoding: none\r\n");

			ob_end_flush();
			ob_flush();
			flush();

			if (session_id()) session_write_close();

			// start background process. Clever. HUH?
			USP_Category::send_activation_link(USP_Category::$last_subscriber);
		}
		else if ($response_code === USP_Category::ADD_SUBSCRIBER_ALREADY_SUBSCRIBED) {
			self::dont_show_popup_for_a_day($form_id);
			$message = __("You're already subscribed!", 'ultimate-subscribe-pro');
		}
		else if ($response_code === USP_Category::ADD_SUBSCRIBER_BAD_EMAIL) {
			$message = __('Please enter a valid email!', 'ultimate-subscribe-pro');
		}
		else if ($response_code === USP_Category::ADD_SUBSCRIBER_AWAITING_CONFIRMATION) {
			$message = __("An email to activate your subscription has already been sent. Be sure to check spam.", 'ultimate-subscribe-pro');
		}
		else {
			$message = __("We're sorry, but subscriptions are temporarily down. Sorry!" . $response_code, "ultimate-subscribe-pro");
		}

		// htmlentities is called upon above translations, as shown below.

		wp_die(htmlentities($message));

	}

	/**
	 * Given a $form_id, this makes the form not show up for a day.
	 */
	private static function dont_show_popup_for_a_day($form_id) {
		// JS Cookie.
		setcookie("usp_$form_id", "true", time() + 86400, COOKIEPATH, COOKIE_DOMAIN);
	}

}