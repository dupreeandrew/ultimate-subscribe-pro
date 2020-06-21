<?php

/**
 * Class Form
 * This class is to write/read subscription forms for the public visitor
 */
class USP_Form {

	private $id;
	private $name;
	private $body;
	private $skin_name;
	private $subscription_buttons;
	private $popup_time;

	/**
	 * Form constructor.
	 * @param $id
	 * @param $name
	 * @param $body
	 * @param $skin_name
	 * @param $subscription_buttons
	 * @param int $is_popup
	 */
	private function __construct($id, $name, $body, $skin_name, $subscription_buttons, $popup_time = -1) {
		$this->id = (int)$id;
		$this->name = esc_html($name);
		$this->body = esc_html($body);
		$this->subscription_buttons = $subscription_buttons; // array of SubscribeButton
		$this->popup_time = (int)$popup_time;

		require_once(USP_BASE_DIR . "views/USP_RegisteredSkins.php");
		if (USP_RegisteredSkins::verify_skin_name($skin_name)) {
			$this->skin_name = $skin_name;
		}
		else {
			$this->skin_name = "Default";
		}
	}

	/**
	 * @param $form_name string name of form
	 * @param $form_body string body of the form
	 * @param $skin_name string (same as template name)
	 * @param array $subscribe_buttons
	 * @param int $popup_time time in between seconds for popup to show per visit. -1 represents never popup.
	 * @param int $form_id (optional) form_id to overrwrite
	 * @return int Returns the form's ID, or -1 for failure.
	 */
	public static function writeForm($form_name, $form_body, $skin_name, $subscribe_buttons, $popup_time = -1, $form_id = -1) {

		require_once(USP_BASE_DIR . 'models/USP_Category.php');

		if (!is_numeric($popup_time)) {
			$popup_time = -1;
		}

		else {
			$popup_time = intval($popup_time);
			if ($popup_time < 0) {
				$popup_time = -1;
			}
		}

		require_once(USP_BASE_DIR . "views/USP_RegisteredSkins.php");
		if (!USP_RegisteredSkins::verify_skin_name($skin_name)) {
			return -1;
		}

		// Escape received variables:
		$form_name = esc_html($form_name);
		$form_body = esc_html($form_body);
		$skin_name = sanitize_text_field($skin_name);

		$post_excerpt_json = json_encode([
			"form_body" => $form_body,
			"skin_name" => $skin_name,
			"popup_time" => $popup_time
		]);

		$post_content = []; // this is where the buttons are stored.

		if ($subscribe_buttons !== null && count($subscribe_buttons) != 0) {
			foreach ($subscribe_buttons as $subscribe_button) {
				$text = $subscribe_button->getText();
				$categoryId = $subscribe_button->getCategoryId();

				$category = USP_Category::getCategory($categoryId);
				if ($category === null) {
					return -1;
				}

				$subscription_button_array = [
					"text" => $text,
					"categoryId" => $categoryId
				];

				$post_content[] = $subscription_button_array;
			}
		}
		$post_content_json = json_encode($post_content);

		$form_details = [
			"post_title" => $form_name,
			"post_excerpt" => $post_excerpt_json,
			"post_content" => $post_content_json,
			"post_type" => "usp_form",
			"ping_status" => "closed",
			"comment_status" => "closed",
			"post_status" => "published"
		];

		if ($form_id !== -1 && USP_Form::getForm($form_id) !== null) {
			$form_details["ID"] = $form_id;
			wp_update_post($form_details);
			return $form_id;
		}


		wp_insert_post($form_details);
		global $wpdb;
		return ($wpdb->insert_id > 0)
			? $wpdb->insert_id
			: -1;
	}

	/**
	 * @param $form_id int id of the form
	 * @return USP_Form|null
	 */
	public static function getForm($form_id) {

		require_once(USP_BASE_DIR . 'views/USP_SubscribeButton.php');

		$form = get_post($form_id, ARRAY_A);
		if ($form === null) {
			return null;
		}
		if ($form['post_type'] !== "usp_form") {
			return null;
		}

		$clean_post_excerpt_json = stripslashes($form['post_excerpt']);
		$post_excerpt = json_decode($clean_post_excerpt_json, true);
		$form_body = $post_excerpt['form_body'];
		$skin_name = $post_excerpt['skin_name'];
		$popup_time = $post_excerpt['popup_time'];

		$subscription_buttons_array = json_decode(stripslashes($form['post_content']), true);
		$subscription_buttons = [];
		foreach ($subscription_buttons_array as $subscription_button_array) {
			$text = $subscription_button_array["text"];
			$category_id = $subscription_button_array["categoryId"];
			$subscription_buttons[] = new USP_SubscribeButton($text, $category_id);
		}

		return new USP_Form($form_id, $form['post_title'], $form_body, $skin_name, $subscription_buttons, $popup_time);

	}

	/**
	 * Retrieve an array of form details, containing "id" & "name" & "shortcode"
	 */
	public static function get_all_form_details() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . "posts";
		$query = "SELECT id, post_title FROM $table_name WHERE post_type='usp_form'";
		$results = $wpdb->get_results($query, ARRAY_A);
		$formDetails = [];
		foreach ($results as $result) {
			$formDetails[] = [
				"id" => $result['id'],
				"name" => $result['post_title'],
				"shortcode" => "[subscribe_pro form={$result['id']}]"
			];
		}
		return $formDetails;
	}

	/**
	 * @return mixed
	 */
	public function get_id()
	{
		if (!is_numeric($this->id)) {
			return -1;
		}

		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function get_name()
	{
		return sanitize_text_field($this->name);
	}

	/**
	 * @return mixed
	 */
	public function get_body()
	{
		return sanitize_text_field($this->body);
	}

	/**
	 * @return mixed
	 */
	public function get_skin_name()
	{
		return sanitize_text_field($this->skin_name);
	}

	/**
	 * @return mixed
	 */
	public function get_subscription_buttons()
	{
		return $this->subscription_buttons;
	}

	/**
	 * @return int time in seconds between popup. Will return -1 if popup is not enabled
	 */
	public function get_popup_time() {
		if (!is_numeric($this->popup_time)) {
			return -1;
		}
		return $this->popup_time;
	}

	/**
	 * Delete the form from the database.
	 */
	public function delete() {
		wp_delete_post($this->id);
	}

}