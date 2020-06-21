<?php

/*
 * So the page that would be rendered would be a table of admin.
 */

class USP_ViewFormsController implements USP_Controller {

	public function init() {

		require_once(USP_BASE_DIR . "models/USP_Form.php");
		require_once(USP_BASE_DIR . "views/admin/USP_ViewFormsView.php");

		$view_forms_title = esc_html__("View Forms", "ultimate-subscribe-pro");
		add_submenu_page("subscribepro",
			$view_forms_title,
			$view_forms_title,
			"manage_options",
			"subscribepro_forms",
			[$this, 'render_forms_page']);
	}

	public function render_forms_page() {

		if (isset($_GET['form_added'])) {
			$msg = esc_html__("Form was added/updated!", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($msg, true);
		}

		$this->check_form_deleted();

		$all_form_details = USP_Form::get_all_form_details();
		USP_ViewFormsView::renderHTML($all_form_details);
	}

	private function check_form_deleted() {
		if (!isset($_GET['action']) || $_GET['action'] !== "delete") {
			return;
		}

		if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			return;
		}

		$form = USP_Form::getForm($_GET['id']);
		if ($form !== null) {
			$form->delete();
			$msg = esc_html__("Form was deleted!", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($msg, true);
		}

	}

}