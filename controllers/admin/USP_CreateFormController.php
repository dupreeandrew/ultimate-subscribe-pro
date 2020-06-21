<?php


class USP_CreateFormController implements USP_Controller {

	public function init() {

		require_once(USP_BASE_DIR . "models/USP_Form.php");
		require_once(USP_BASE_DIR . "views/admin/USP_CreateFormView.php");
		require_once(USP_BASE_DIR . "models/USP_Category.php");
		require_once(USP_BASE_DIR . "views/USP_SubscribeButton.php");

		$create_form_title = esc_html__("Create Form", "ultimate-subscribe-pro");
		add_submenu_page("subscribepro",
			$create_form_title,
			$create_form_title,
			"manage_options",
			"subscribepro_create_form",
			[$this, 'render_add_form_page']);
	}

	public function render_add_form_page() {
		$form = $this->check_form_edit();
		$this->check_form_submission();
		$category_data = $this->get_category_data();

		require_once(USP_BASE_DIR . "views/USP_RegisteredSkins.php");
		$registered_templates = USP_RegisteredSkins::get_registered_templates();
		USP_CreateFormView::renderHTML($category_data, $registered_templates, $form);
	}

	/**
	 * Checks to see if a user
	 */
	private function check_form_edit() {
		if (!isset($_GET['id'])) {
			return null;
		}

		$form = USP_Form::getForm($_GET['id']);
		return $form;

	}

	private function check_form_submission() {

		$could_not_create_form_msg = esc_html__('Could not create form', 'ultimate-subscribe-pro');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return;
		}

		$title = sanitize_text_field($_POST['form_title']);
		$body = esc_html($_POST['form_body']);
		$skinName = sanitize_text_field($_POST['form_skin']);
		$subscription_buttons = $this->get_subscription_buttons();

		$popup_time = $this->get_popup_time();

		$edited_form_id =  (isset($_GET['id'])) ? $_GET['id'] : -1;
		$form_id = USP_Form::writeForm($title, $body, $skinName, $subscription_buttons, $popup_time, $edited_form_id);
		if ($form_id !== -1) {
			$this->redirectToCreateForms();
		}
		else {
			USP_Announcer::echoNotification($could_not_create_form_msg);
		}

	}

	private function redirectToCreateForms() {
		echo '<script type="text/javascript">' . 'window.location = "' . USP_URLGetter::getViewFormsPage() . '&form_added=1"' . '</script>';
	}

	private function get_subscription_buttons() {

		if (!isset($_POST['category_id'])) {
			$categoryIds = [];
		}
		else {
			$categoryIds = $_POST['category_id']; // data is validated below
		}

		if (!isset($_POST['button_text'])) {
			$buttonTexts = [];
		}
		else {
			$buttonTexts = $_POST['button_text']; // data is validated below
		}

		if (!is_array($categoryIds) || !is_array($buttonTexts)) {
			$categoryIds = [];
			$buttonTexts = [];
		}


		if (!is_array($categoryIds))

		$subscription_buttons = [];
		foreach ($categoryIds as $index => $categoryId) {
			$buttonText = sanitize_text_field($buttonTexts[$index]);
			$subscription_buttons[] = new USP_SubscribeButton($buttonText, (int)$categoryId);
		}

		return (!isset($subscription_buttons)) ? null : $subscription_buttons;
	}

	private function get_popup_time() {

		if (!isset($_POST['is_popup'])) {
			return -1;
		}

		if ($_POST['is_popup'] !== "checked") {
			return -1;
		}

		if (!isset($_POST['is_popup_time'])) {
			return -1;
		}

		$popup_time = $_POST['is_popup_time'];
		if (!is_numeric($popup_time)) {
			return -1;
		}
		if ($popup_time < 0) {
			return -1;
		}
		return $popup_time;
	}

	private function get_category_data() {
		$categories = USP_Category::getAllCategories();

		if (count($categories) === 0) {
			return "";
		}

		$categoryData = [];
		foreach ($categories as $category) {
			$categoryData[] = [
				"id" => (int)$category->getId(),
				"name" => esc_html($category->getName()),
			];
		}
		return json_encode($categoryData);
	}
}