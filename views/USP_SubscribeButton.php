<?php

/**
 * Class USP_SubscribeButton
 * This class is a data class that holds the text and category_id for a subscription button.
 */
class USP_SubscribeButton {

	private $text;
	private $categoryId;

	/**
	 * SubscribeButton constructor.
	 * @param $text
	 * @param $categoryId
	 */
	public function __construct($text, $categoryId)
	{
		$this->text = sanitize_text_field($text);
		$this->categoryId = (int)$categoryId;
	}

	/**
	 * @return mixed
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @return mixed
	 */
	public function getCategoryId()
	{
		return $this->categoryId;
	}





}