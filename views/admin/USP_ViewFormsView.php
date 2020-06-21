<?php


class USP_ViewFormsView {
	/**
	 * @param $all_form_details array Array from #USP_Form::get_all_form_details
	 */
	public static function renderHTML($all_form_details, $display_thank_you_message = true) {
		include(USP_BASE_DIR . "templates/admin/view-forms.php");
	}
}