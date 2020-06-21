<?php
/**
 * This is the popup that gets sent whenever someone attempts to activate/deactivate their account.
 *
 * Translations for $title and $body are available in /models/USP_CodeListener.php, lines #33~53
 *
 */

$site_url = site_url();
header( "Refresh:7; url=$site_url", true, 303);

// wp_enqueue_style() is not used here because under the circumstance, WordPress will not load it.

?>

<html>
<head>
    <title><?php echo esc_html($title); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo USP_BASE_URL . "css/unique-code.css"; ?>">
</head>
<body>

	<div class="jumbotron">
		<h1><?php echo esc_html($title); ?></h1>
		<p><?php echo esc_html($body); ?></p>
		<p><?php esc_html_e('You will be redirected shortly..', 'ultimate-subscribe-pro'); ?></p>
		<p><a href="<?php echo site_url(); ?>"><?php esc_html_e('(Or click here if it\'s been longer than 5 seconds)', 'ultimate-subscribe-pro'); ?></a></p>
	</div>

</body>
</html>