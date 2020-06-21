<?php


class USP_HelpController implements USP_Controller {
	public function init() {
		$help_title = esc_html__("Help", 'ultimate-subscribe-pro');
		add_submenu_page("subscribepro",
			$help_title,
			$help_title, "manage_options",
			"subscribepro_help",
			[$this, 'load_help_page']);
	}

	public function load_help_page() {
		require_once(USP_BASE_DIR . "views/admin/USP_HelpView.php");
		USP_HelpView::renderHTML();
	}

}