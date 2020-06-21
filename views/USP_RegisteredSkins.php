<?php

/**
 * This class will register all the available templates.
 * Template-loading is fully automatic through this class.
 * There is no need to change other code to add a template.
 * Do follow a_skin_note.txt though, in /templates/
 */
class USP_RegisteredSkins {

	private const TEMPLATE_DIR = USP_BASE_DIR . "templates/";
	private const REGISTERED_TEMPLATES = [
		"Default" => self::TEMPLATE_DIR . "skin-default.php", // DO NOT DELETE OR MOVE FROM 1ST INDEX.
		"Basic"   => self::TEMPLATE_DIR . "skin-basic.php",
		"Smooth"  => self::TEMPLATE_DIR . "skin-smooth.php",
		"Lady"    => self::TEMPLATE_DIR . "skin-lady.php",
		"Blue"    => self::TEMPLATE_DIR . "skin-blue.php"
		// just add templates here.

	];

	/**
	 * @param $name string Name of the template file
	 * @return string File location of the template file. Returns default if name doesn't exist.
	 */
	public static function get_template_file($name) {
		if (array_key_exists($name, self::REGISTERED_TEMPLATES)) {
			return self::REGISTERED_TEMPLATES[$name];
		}
		return self::REGISTERED_TEMPLATES["Default"];
	}

	/**
	 * @return array the map of skin names against their file location.
	 */
	public static function get_registered_templates() {
		return self::REGISTERED_TEMPLATES;
	}

	public static function verify_skin_name($skin_name) {
		return array_key_exists($skin_name, self::REGISTERED_TEMPLATES);
	}
}