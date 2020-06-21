<?php


class USP_Subscriber {

	private $name;
	private $email;

	/**
	 * Subscriber constructor.
	 * @param $name String name of subscriber
	 * @param $email String email of subscriber
	 */
	public function __construct($name, $email) {
		$this->name = sanitize_text_field($name);
		$this->email = sanitize_email($email);
	}

	/**
	 * @return String name of subscriber
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return String email of subscriber
	 */
	public function getEmail() {
		return $this->email;
	}

}