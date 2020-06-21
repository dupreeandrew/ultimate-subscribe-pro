<?php

/**
 * Class USP_URLGetter
 * This class is responsible for providing common links that may be used throughout code.
 */
class USP_URLGetter {

	public static function getAdminCategoryPage() {
		return admin_url('admin.php?page=subscribepro_categories');
	}

	public static function getCreateFormPage() {
		return admin_url('admin.php?page=subscribepro_create_form');
	}

	public static function getViewFormsPage() {
		return admin_url('admin.php?page=subscribepro_forms');
	}

	public static function getEmailSettingsPage() {
		return admin_url('admin.php?page=subscribepro_email_settings');
	}

	public static function getSendEmailPage() {
		return admin_url('admin.php?page=subscribepro_send_emails');
	}

}