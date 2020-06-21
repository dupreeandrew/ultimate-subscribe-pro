<?php

/**
 * Class USP_FormCreator
 * This class is responsible for converting a $form object into pure HTML.
 *
 * If you are a developer reading this, the realtime form creation menu in the admin section
 * is not created using this, due to complexity of the real time visualizer.
 */
class USP_FormCreator {

	private $skin_name;
	private $title;
	private $body;
	private $subscribe_buttons;
	private $form;

	/**
	 * FormCreator constructor.
	 */
	public function __construct($form) {
		$this->form = $form;
	}

	public function getHTML() {

		// impossible, since form is validated beforehand in USP_Shortcode.php line #68, but hey, escaping.
		if ($this->form === null) {
			return esc_html__("Invalid form.", 'ultimate-subscribe-pro');
		}

		// all methods are sanitized by the Form object before it it retrieves the value
		// same applies for SubscribeButton class.

		$skin_name = esc_html($this->form->get_skin_name());
		$title = esc_html($this->form->get_name());
		$body = esc_html($this->form->get_body());
		$html_buttons = $this->get_html_buttons(); // function returns sanitized data
		$is_popup = ($this->form->get_popup_time() >= 0);

		require_once(USP_BASE_DIR . "views/USP_RegisteredSkins.php");
		$skin_file = USP_RegisteredSkins::get_template_file($skin_name);

		ob_start();
		include($skin_file);
		return ob_get_clean();

	}


	private function get_html_buttons() {

		$form_id = (int)$this->form->get_id();

		$html = "";
		$html .= "<input name =\"form_id\" value=\"$form_id\" type=\"hidden\">"; // unrelated code per method name, but, we need this everytime this method is invoked, so why not.
		$html .= '<input name="action" value="usp_submit_new_subscriber" type="hidden">';
		$html .= "<input name =\"category_id\" value=\"\" type=\"hidden\">"; // javascript won't serialize below
		foreach ($this->form->get_subscription_buttons() as $subscribe_button) {

			$text = esc_html($subscribe_button->getText());
			$categoryId = (int)$subscribe_button->getCategoryId();


			$html .= "<button onclick=\"this.form.category_id.value=$categoryId\" type=\"submit\">$text</button>";

		}
		return $html;
	}




}