<?php

/**
 * This file exports a category's subscriber list to a CSV file.
 * Category ID is given through $_GET['id']
 */

require_once '../../../wp-load.php';

if (!is_user_logged_in()) {
	wp_die("");
}

if (!current_user_can("manage_options")) {
	wp_die("");
}

require_once('models/USP_Category.php');

$usp_category_id = (int)$_GET['id'];

$usp_category = USP_Category::getCategory($usp_category_id);

if ($usp_category === null) {
	wp_die(""); // any safe user shouldn't encounter this.
}

$usp_category->echo_subscriber_csv();


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . preg_replace('/\s/', '-', sanitize_text_field($usp_category->getName())) . '-subscribers');