<?php


class USP_ViewSubscribersView {

	public static function renderHTML($category_name, $subscriber_name_email_timestamp_array) {
		$category_name = sanitize_text_field($category_name);
		include(USP_BASE_DIR . "templates/admin/view-subscribers.php");
	}

}