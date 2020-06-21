<?php
/**
 * Plugin Name: Ultimate Subscribe Pro
 * Description: The ultimate email subscription management system, offering effective yet simple solutions.
 * Version: 1.0.0
 * Requires at least: 4.7
 * Author: Auburn Designs
 * Text Domain: ultimate-subscribe-pro
 * Domain Path: /languages
 */

function usp_set_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);

	// create link
	if ($file === $plugin) {
		$view_details_msg = esc_html__("View Details", "ultimate-subscribe-pro");
		return array_merge(
			$links,
			["<a href=\"admin.php?page=subscribepro_forms\">$view_details_msg</a>"]
		);
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'usp_set_plugin_meta', 10, 2 );


define('USP_BASE_DIR', dirname(__FILE__) . '/' );
define('USP_BASE_URL', plugins_url("/", __FILE__));

// Common files
require_once(USP_BASE_DIR . "models/USP_URLGetter.php");
require_once(USP_BASE_DIR . "views/USP_Announcer.php");


// Plugin Activation
function subscribepro_activation() {
	global $wpdb;
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->base_prefix}usp_subscribers ("
		. "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, "
		. "category_id INT NOT NULL, "
		. "name VARCHAR(50) NOT NULL DEFAULT '', "
		. "email VARCHAR(80) NOT NULL, "
		. "timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, "
		. "unique_code CHAR(32) NOT NULL, " // 32 is from USP_Category. Check it out.
		. "verified BOOL NOT NULL DEFAULT FALSE"
		. ") {$wpdb->get_charset_collate()}";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);

	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->base_prefix}usp_spam ("
		. "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, "
		. "ip_addr_md5 CHAR(32) NOT NULL UNIQUE, "
		. "times INT NOT NULL DEFAULT 0"
		. ") {$wpdb->get_charset_collate()}";
	dbDelta($sql);

	wp_schedule_event(time(), 'daily', 'usp_clear_spam_table');


}
register_activation_hook(__FILE__, 'subscribepro_activation');

function subscribepro_clear_spam_table() {
	global $wpdb;
	$table  = $wpdb->base_prefix . "usp_spam";
	$wpdb->query("TRUNCATE TABLE $table");
}
add_action('usp_clear_spam_table', 'subscribepro_clear_spam_table');


// Plugin Uninstall
function subscribepro_uninstall() {
	global $wpdb;
	$sql = "DROP TABLE '{$wpdb->get_charset_collate()}_usp_subscribers'";
	$wpdb->query($sql);

	//$sql = "SELECT post_id"
}
register_uninstall_hook(__FILE__, 'subscribepro_uninstall');

// Plugin Deactivation
function subscribepro_deactivate() {
	wp_clear_scheduled_hook('usp_clear_spam_table');
}
register_deactivation_hook(__FILE__, 'subscribepro_deactivate');

// --- Shortcode Listener--- //
include(USP_BASE_DIR . "models/USP_Shortcode.php");
$usp_shortcode = new USP_Shortcode();
$usp_shortcode->start_listening();

// --- Menu/Submenu Creator --- //
function subscribepro_register_menu() {
	add_menu_page("Subscribers", "SubscribePro", 'manage_options', 'subscribepro', null, 'dashicons-email-alt2');

	require_once(USP_BASE_DIR . "controllers/USP_Controller.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_ViewFormsController.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_CreateFormController.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_CategoryController.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_SendEmailController.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_EmailSettingsController.php");
	require_once(USP_BASE_DIR . "controllers/admin/USP_HelpController.php");

	$controllers = [
		new USP_ViewFormsController(),
		new USP_CreateFormController(),
		new USP_CategoryController(),
		new USP_SendEmailController(),
		new USP_EmailSettingsController(),
		new USP_HelpController()
	];

	foreach ($controllers as $controller) {
		$controller->init();
	}

	remove_submenu_page("subscribepro", "subscribepro");
}
add_action('admin_menu', 'subscribepro_register_menu');

// Ajax Listener
require_once(USP_BASE_DIR . "models/USP_AjaxHandler.php");
USP_AjaxHandler::init();

// Activation Code Listener
add_action('init', 'subscribepro_start_activation_code_listener');
function subscribepro_start_activation_code_listener() {
	require_once(USP_BASE_DIR . "models/USP_CodeListener.php");
	USP_CodeListener::start_listening();
}

// Form Button HTML Sanitizer.
function usp_sanitize_html_button_payload($html_button) {
	return wp_kses($html_button, [
		"input" => [
			"name" => [],
			"type" => [],
			"value" => []
		],
		"button" => [
			"onclick" => [], // the value here can't be changed by user input. It's all hard coded.
			"type" => []
		]
	]);
}