<?php

/**
 * Class USP_Category
 * This class allows you to create/modify categories
 */
class USP_Category {

	public const ADD_SUBSCRIBER_SUCCESS = 0;
	public const ADD_SUBSCRIBER_NEEDS_CONFIRMATION_EMAIL = 1;
	public const ADD_SUBSCRIBER_FAIL = 2;
	public const ADD_SUBSCRIBER_ALREADY_SUBSCRIBED = 3;
	public const ADD_SUBSCRIBER_BAD_EMAIL = 4;
	public const ADD_SUBSCRIBER_AWAITING_CONFIRMATION = 5;

	private $id;
	private $name;
	public static $last_subscriber;

	/**
	 * Category constructor.
	 * @param $id
	 * @param $name
	 */
	private function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}

	/**
	 *
	 * Writes a category to the database.
	 *
	 * @param $category_name string Name of the category
	 * @return int category ID, or -1 if unsuccessful.
	 *
	 */
	public static function write($category_name) {

		$category_name = sanitize_text_field($category_name);

		global $wpdb;

		wp_insert_post([
			"post_title" => $category_name,
			"post_status" => "publish",
			"post_type" => "usp_category",
			"comment_status" => "closed",
			"ping_status" => "closed"
		]);

		if ($wpdb->insert_id >= 0) {
			return $wpdb->insert_id;
		}
		else {
			return -1;
		}
	}

	/**
	 * Deletes a given category ID.
	 * @return boolean success/failure, based on if $category_id is valid.
	 */
	public static function delete($category_id) {

		if (!is_numeric($category_id)) {
			return false;
		}

		$post = get_post($category_id, ARRAY_A);

		if ($post === null) {
			return false;
		}

		if ($post['post_type'] !== "usp_category") {
			return false;
		}

		wp_delete_post($category_id);

		global $wpdb;
		$wpdb->delete($wpdb->base_prefix . "usp_subscribers", [
			"category_id" => $category_id
		]);
		return true;
	}

	/**
	 * @param $category_id int id of the category
	 * @return USP_Category Returns the category from its given ID, or null if unsuccessful
	 */
	public static function getCategory($category_id) {

		if (!is_numeric($category_id)) {
			return null;
		}

		$post = get_post($category_id, ARRAY_A);

		if ($post === null) {
			return null;
		}

		if ($post['post_type'] !== "usp_category") {
			return null;
		}

		return new USP_Category($category_id, $post['post_title']);

	}

	/**
	 * @return array an array of all the categories that exist.
	 */
	public static function getAllCategories() {
		global $wpdb;
		$query = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='usp_category' ORDER BY post_date DESC";
		$results = $wpdb->get_results($query, ARRAY_A);
		$categories = [];
		foreach ($results as $result) {
			$categoryId = (int)$result['ID'];
			$categoryName = sanitize_text_field($result['post_title']);
			$categories[] = new USP_Category($categoryId, $categoryName);
		}
		return $categories;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Adds a subscriber to the category.
	 * The added subscriber is saved into static::$last_subscriber
	 */
	public function add_subscriber($name, $email) {

		if ($this->is_spammer()) {
			return self::ADD_SUBSCRIBER_FAIL;
		}

		if (!is_user_logged_in() && !current_user_can("manage_options")) {
			$this->add_ip_to_spam_record();
		}

		$name = sanitize_text_field($name);
		$email = sanitize_email($email);

		if (!is_email($email)) {
			return self::ADD_SUBSCRIBER_BAD_EMAIL;
		}

		global $wpdb;

		$check_exists_query = "SELECT id, verified FROM " . "{$wpdb->base_prefix}usp_subscribers" . " WHERE category_id = %d AND email = %s";
		$sql = $wpdb->prepare( $check_exists_query, [$this->id, $email] );
		$results = ($wpdb->get_results($sql , ARRAY_A ));
		if (count($results) > 0) {

			if ((int)$results[0]['verified'] === 1) {
				return self::ADD_SUBSCRIBER_ALREADY_SUBSCRIBED;
			}
			else {
				return self::ADD_SUBSCRIBER_AWAITING_CONFIRMATION;
			}

		}

		require('USP_EmailSettings.php');
		$send_activation_link = USP_EmailSettings::get_email_settings()['use_confirmation_email'];

		$unique_code = $this->generate_unique_code();

		$result = $wpdb->insert($wpdb->base_prefix . "usp_subscribers", [
			"category_id" => $this->id,
			"name" => $name,
			"email" => $email,
			"unique_code" => $unique_code,
			"verified" => $send_activation_link ? 0 : 1
		], [
			"%d",
			"%s",
			"%s",
			"%s"
		]);

		if ($result !== false) {
			if ($send_activation_link) {
				self::$last_subscriber = [
					"email" => $email,
					"name" => $name,
					"unique_code" => $unique_code
				];

				return self::ADD_SUBSCRIBER_NEEDS_CONFIRMATION_EMAIL;
			}
			else return self::ADD_SUBSCRIBER_SUCCESS;
		}
		else {
			return self::ADD_SUBSCRIBER_FAIL;
		}
	}

	/**
	 * Increments the spam count of the currently-connected user to the spam database.
	 * This number should be incremented even if the user isn't a spammer.
	 * The spam table is wiped out daily so records aren't permanent.
	 * Call #is_spammer() check if user is too much of a spammer.
	 */
	private function add_ip_to_spam_record() {
		$ip_addr_md5 = $this->get_md5_ip();
		global $wpdb;
		$query = "INSERT INTO {$wpdb->base_prefix}usp_spam (ip_addr_md5) "
			. "VALUES ('" . $ip_addr_md5 . "') "
			. "ON DUPLICATE KEY UPDATE times = times+1";
		$wpdb->query($query);
	}

	private function get_md5_ip() {
		$ip_addr = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
		return md5($ip_addr);
	}

	/**
	 * The first time a user signs up, the spam count is set to 0.
	 * Any more attempts will add +1 to their spam counter.
	 */
	private function is_spammer() {
		global $wpdb;
		$query = "SELECT times FROM {$wpdb->base_prefix}usp_spam "
			. 'WHERE ip_addr_md5="' . $this->get_md5_ip() . '"';
		$times = $wpdb->get_var($query);
		$times = intval($times);

		return ($times >= 3);
	}

	/**
	 * Generates a random unique code of 32 characters.
	 */
	private function generate_unique_code() {
		$length = 32;
		try {
			$code = bin2hex(random_bytes(($length + 2) / 2));
			$code = substr($code, 0, $length);
			return $code;
		}
		catch (Exception $e) {
			return substr(md5(uniqid("abcdefghijklmnop")), 0, $length);
		}
	}

	/**
	 * Sends an activation link to the last person that just subscribed since this script was loaded.
	 */
	public static function send_activation_link($last_subscriber) {
		$email = $last_subscriber['email'];
		$name = $last_subscriber['name'];
		$unique_code = $last_subscriber['unique_code'];

		$phpmailer = USP_EmailSettings::get_phpmailer();
		$phpmailer->addAddress($email, $name);
		$phpmailer->Subject = "Thanks for subscribing!";

		$activation_url = site_url() . "/?action=confirm-subscriber-code&email=" . urlencode($email) . "&code=$unique_code";
		$body = USP_EmailSettings::get_email_settings()['confirmation_body'];
		$body = self::replace_placeholders($body, $name, $activation_url);

		$phpmailer->Body = $body;
		try {
			$phpmailer->send();
		} catch (phpmailerException $e) {
			USP_Announcer::echoNotification("Could not send mail:" . $e->getMessage());
		}
	}

	/**
	 * Replaces placeholder(s) such as {subscriber_name} with the given parameter(s)
	 */
	private static function replace_placeholders($body, $name, $activation_url) {
		$body = str_replace("http://{activation_link}", $activation_url, $body);
		$body = str_replace("{activation_link}", $activation_url, $body);
		$body = str_replace("{subscriber_name}", $name, $body);
		return $body;
	}

	/**
	 * @return bool returns true if activation code was real.
	 */
	public static function confirm_activation_code($email, $code) {
		global $wpdb;
		$result = $wpdb->update($wpdb->base_prefix . "usp_subscribers", [
			"verified" => 1
		], [
			"verified" => 0,
			"email" => $email,
			"unique_code" => $code
		]);

		if ($result === false) {
			return false;
		}

		if ($result === 0) {
			return false;
		}

		return true;

	}

	/**
	 * Removes a subscriber from the database.
	 * No category is needed, since the chance of a subscriber having the same code in > 1 categories is nearly impossible.
	 * @param $email string email of the subscriber
	 * @param $code string unique code of the subscriber
	 * @return bool returns true if subscriber was deleted
	 */
	public static function remove_subscriber($email, $code) {
		global $wpdb;
		$result = $wpdb->delete($wpdb->base_prefix . "usp_subscribers", [
			"verified" => 1,
			"email" => $email,
			"unique_code" => $code
		]);

		return self::get_boolean_from_result($result);

	}

	private static function get_boolean_from_result($result) {
		if ($result === false) {
			return false;
		}

		if ($result === 0) {
			return false;
		}

		return true;
	}

	public static function force_remove_subscriber($email) {
		global $wpdb;
		$result = $wpdb->delete($wpdb->base_prefix . "usp_subscribers", [
			"email" => $email
		]);

		return self::get_boolean_from_result($result);
	}

	/**
	 * Echos a CSV of subscribers, with headers: "name" & "email
	 */
	public function echo_subscriber_csv() {

		// mysqli has to be directly used, since wpdb::get_results stores everything into an array, and maybe even objects.
		// this can lead to insane memory issues when querying a large dataset.
		// instead, let's just retrieve the row one by one, echoing the content.

		echo "name, email\n";
		$this->get_subscribers(function($name, $email) {
			echo esc_html("$name, $email") . "\n";
		});

	}

	/**
	 * Invoke $subscriber_function() to every registered subscriber.
	 * @param $subscriber_function callable function.
	 * $subscriber_function parameters: $name, $email, $unique_code, $timestamp;
	 */
	public function get_subscribers($subscriber_function, $limit = -1) {

		$limit = (int)$limit;
		$id = (int)$this->id;

		global $wpdb;
		$query = "SELECT name, email, timestamp, unique_code FROM {$wpdb->base_prefix}usp_subscribers "
			. "WHERE category_id={$id} AND verified=1 "
			. "ORDER BY id DESC ";

		if ($limit > 0) {
			$query .= "LIMIT $limit";
		}

		$results = $wpdb->get_results($query, ARRAY_A);

		foreach ($results as $result) {
			$name = sanitize_text_field($result['name']);
			$email = sanitize_email($result['email']);
			$unique_code = sanitize_text_field($result['unique_code']);
			$timestamp = sanitize_text_field($result['timestamp']);
			$subscriber_function($name, $email, $unique_code, $timestamp);
		}

		// todo:: trivial, but like any programming language, arrays can take up RAM. Perhaps look into ways that
		// can support a billion subscribers? Maybe, maybe not.

	}



}