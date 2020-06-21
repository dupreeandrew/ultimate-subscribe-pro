<?php
/**
 * This file MUST be included at the top of your skin file.
 *
 * Also, $title, $body, and $html_buttons do NOT need translation
 * because the visible text is directly produced by the client.
 *
 * To see a comprehensive list of variables you're given, see "a_skin_note.txt"
 *
 */

if (isset($fake_form) && $fake_form) {
	// A fake form is considered the form that's shown to the user that's creating a form from the template editor
	// in the admin section. A non-fake form is the form that visitors see via shortcode.
	$title = esc_html($title);
	$body = esc_html($body);
	$html_buttons = "";
	$is_popup = false;
	$fake_form = true;
}
else {

	// It's a real form.
	$title = esc_html($title);
	$body = esc_html($body);

	/*
	 * If it's a real form, skins are 100.000% being created from /views/USP_FormCreator.php
	 * =!= $html_buttons is properly escaped and validated in method #get_html_buttons() line #46. =!=
	 * #get_html_buttons() is invoked at line #33,
	 * while the skin (that's including this file) is included at line #37.
	 */

	$fake_form = false;

}

