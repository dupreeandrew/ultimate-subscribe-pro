<?php

class USP_Shortcode {

	public function start_listening() {
		add_action('init', [$this, 'add_shortcode']);
		add_action('init', [$this, 'cookie_hacker']);
	}

	/**
	 * @access private
	 */
	public function add_shortcode() {
		add_shortcode("subscribe_pro", [$this, 'get_shortcode_response']);
	}

	/**
	 * Basically this function sets a cookie for how long a form shouldn't pop up, if it's supposed to.
	 * Because it's impossible to set cookies once the shortcode event has been fired,
	 * the client will have to download the cookie through an AJAX call.
	 */
	public function cookie_hacker() {

		if (!isset($_GET['usp_cookie_name']) && !isset($_GET['usp_cookie_time'])) {
			return;
		}

		if (isset($_SERVER['HTTP_REFERER'])) {
			$referrer_domain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
			if ($_SERVER['SERVER_NAME'] !== $referrer_domain) {
				return;
			}
		}

		$cookie_name = $_GET['usp_cookie_name'];
		if (substr($cookie_name, 0, 4) !== "usp_") {
			return;
		}

		if (!is_numeric(($_GET['usp_cookie_time']))) {
			return;
		}

		$cookie_duration = intval($_GET['usp_cookie_time']);
		setcookie($cookie_name, "true", time() + $cookie_duration, COOKIEPATH, COOKIE_DOMAIN);

	}

	/**
	 * Returns HTML given an appropriate shortcode atts.
	 * @access private
	 */
	public function get_shortcode_response($atts) {

		require_once(USP_BASE_DIR . "models/USP_Form.php");
		require_once(USP_BASE_DIR . "views/USP_FormCreator.php");

		$atts = shortcode_atts([
			'form' => -1
		], $atts);

		$formId = $atts['form'];
		if ($formId === -1) {
			return esc_html__("Invalid form id", 'ultimate-subscribe-pro');
		}

		$form = USP_Form::getForm($formId);

		if ($form === null) {
			return esc_html__("Invalid form id", 'ultimate-subscribe-pro');
		}

		$html = "";
		if ($form->get_popup_time() >= 0) {
			wp_enqueue_style("jsmodalcss", USP_BASE_URL . "lib/jquery-modal/jquery.jqmodal.css");
			$parent_id = 'usp-js-' . sanitize_text_field($form->get_skin_name());
			$html .= '<div id="' . $parent_id . '" class="jqmodal">';

			if ($this->cookie_permits_popup($form)) {
				wp_enqueue_script("jqmodaljs", USP_BASE_URL . "lib/jquery-modal/jquery.jqmodal.js", ["jquery"]);
				wp_localize_script("jqmodaljs", 'id_of_modal', ["id" => $parent_id]);
				// the above will make the form pop up
			}

		}

		wp_enqueue_script("usp-form-submission", USP_BASE_URL . "js/form-submission.js", ["jquery"]);

		$formCreator = new USP_FormCreator($form);
		$html .= $formCreator->getHTML();
		if ($form->get_popup_time() >= 0) {
			$html .= "</div>";
		}

		return $html;

	}

	private function cookie_permits_popup($form) {

		$cookie_name = "usp_" . $form->get_id();
		if (!isset($_COOKIE[$cookie_name])) {
			if ($form->get_popup_time() > 0) {
				self::disable_form_popup_thru_ajax_cookie($form->get_id(), $form->get_popup_time());
			}
			return true;
		}
		else {
			return false;
		}

	}

	public static function disable_form_popup_thru_ajax_cookie($form_id, $time_in_seconds) {
		wp_enqueue_script("usp-cookie-hacker", USP_BASE_URL . "js/cookie-hacker.js", ["jquery"]);
		wp_localize_script("usp-cookie-hacker", "usp_cookie_data", [
			'usp_cookie_name' => "usp_" . $form_id,
			'usp_cookie_time' => $time_in_seconds
		]);
	}
}