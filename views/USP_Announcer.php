<?php

/**
 * Class USP_Announcer
 * This class can be used to create a temporary announcement in the admin section.
 * PLEASE INTERNAL USE ONLY. $message should be hardcoded.
 */
class USP_Announcer {
	/**
	 * @param $message string message of the announcement
	 * @param bool $applySuccessClass add a green outline
	 */
	public static function echoNotification($message, $applySuccessClass = false) { ?>
		<div class="notice <?php if ($applySuccessClass) echo "notice-success"; ?> is-dismissible">
			<p><strong><?php echo esc_html($message); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html__('Dismiss this notice', 'ultimate-subscribe-pro'); ?></span>
			</button>
		</div>
	<?php }
}