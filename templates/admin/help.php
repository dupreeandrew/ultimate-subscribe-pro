<div class="wrap">
	<h1><?php esc_html_e('Help Section', 'ultimate-subscribe-pro'); ?></h1>

	<h2><?php esc_html_e('About us', 'ultimate-subscribe-pro'); ?></h2>
	<p>
		<?php esc_html_e('First of all, thank you for purchasing Ultimate Subscribe Pro!
		We are a technology company of only two employees, trying to get ourselves out there in the market.
		We will value all types of feedback and suggestions. You can email us at auburndesignstech@gmail.com if you
        need further support! Please read below though to see if anything addresses your concern.', 'ultimate-subscribe-pro'); ?>

	</p>

	<h2><?php esc_html_e('What is this plugin?', 'ultimate-subscribe-pro'); ?></h2>
	<p>
		<?php esc_html_e('This plugin allows you to create responsive subscription forms that allows your visitors to subscribe
		to a category that you made. A category that you make might be called something like "Daily Newsletter",
		"Weekly Deals", or "Deal of the day". Your forms that you create can have multiple subscription buttons,
		allowing for users to subscribe to choose a category that they want to subscribe to.', 'ultimate-subscribe-pro'); ?>

	</p>
	<p>
		<?php esc_html_e('By grouping subscribers into categories, visitors can be more flexible in what type of content they want
		to be emailed. Not all visitors are the same.', 'ultimate-subscribe-pro'); ?>
	</p>

    <h2><?php esc_html_e('What makes this plugin worth it\'s price tag?', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('There\'s a lot of features involved with this plugin! For example:', 'ultimate-subscribe-pro'); ?>
        <ol>
            <li><?php esc_html_e('The ability to make new subscribers click an activation link sent to their email', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('Built in spam-protection, protecting you from a user subscribing to a category many many times', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('The ability to send emails to your subscribers, being able to send a subscription deactivation link, and their name inside the email!', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('A variety of customizable subscription templates all with responsive-design', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('AJAX powered', 'ultimate-subscribe-pro'); ?></li>
        </ol>
    </p>

    <h2><?php esc_html_e('"Your settings were saved, but a test email to your site\'s admin email failed."', "ultimate-subscribe-pro"); ?></h2>
    <p>
		<?php esc_html_e(
			"Every time you save your email settings, a test email is sent to the site's admin email. If you are
                receiving this message, the server more than likely can't use the email settings you have set. To fix this,
                please try reading \"My emails aren't being sent, or they're getting sent to spam\" directly below. 
                Also, if your site's admin email is fake or non-existent, that may trigger this warning.", "ultimate-subscribe-pro"
		); ?>
    </p>

	<h2><?php esc_html_e('My emails aren\'t being sent, or they\'re getting sent to spam', 'ultimate-subscribe-pro'); ?></h2>
	<p>
		<?php esc_html_e('Emails may not be sent for a variety of reasons. The most common reasons are:', 'ultimate-subscribe-pro'); ?>
        <ol>
        <li>
			<?php esc_html_e('You aren\'t using SMTP. Using SMTP isnt mandatory, but it significantly decreases your chances
            of your email getting sent to spam, or simply being non-existent.', 'ultimate-subscribe-pro'); ?>
        </li>
        <li>
			<?php esc_html_e('SMTP is configured incorrectly. Ensure that the email address and password are correct.', 'ultimate-subscribe-pro'); ?>
        </li>
        <li>
			<?php esc_html_e('You reached the daily mail limit for your SMTP provider. Providers like ymail/gmail have a limit of 500/day.', 'ultimate-subscribe-pro'); ?>
        </li>
        <li>
			<?php
            esc_html_e('You\'re using Gmail and less secure apps are disabled. Google likes to deem external apps that have access
            to your email\'s password as "less secure." Nonetheless, SMTP is a widely used protocol that is rather safe. ', 'ultimate-subscribe-pro');

            $url = 'https://myaccount.google.com/lesssecureapps" target="_blank"';
            $allowed_html = ['a' => ["href" => []]];
            printf(wp_kses(__('Log out of all your Google accounts, then click <a href="%s">here</a> to enable them.', 'ultimate-subscribe-pro'), $allowed_html), $url);
            echo " ";
            esc_html_e("Also, if UltimateSubscribePro hasn't been in use for an extended duration of time, you may have to reactivate this setting.", 'ultimate-subscribe-pro')

            ?>
        </li>
        <li>
			<?php esc_html_e('You\'re using Gmail and Gmail blocked your server from logging in. Using your own personal web browser,
            log out of ALL google accounts, and visit ', 'ultimate-subscribe-pro'); ?>
            <a href="https://accounts.google.com/b/0/DisplayUnlockCaptcha" target="_blank">
                https://accounts.google.com/b/0/DisplayUnlockCaptcha
            </a>.
			<?php esc_html_e('The next web server that logs in to your account will be allowed to connect for all future emails.', 'ultimate-subscribe-pro'); ?>
        </li>
        </ol>
	</p>

    <h2><?php esc_html_e('My emails are being sent really slow', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('Usually this problem occurs when you are sending emails to at least 200+ people and you\'re using an external
        SMTP server.
        If you are looking for faster emails, you can either:', 'ultimate-subscribe-pro'); ?>
        <ol>
            <li><?php esc_html_e('Use your own locally hosted SMTP server', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('Export your subscriber list as a CSV and use another solution to send emails.', 'ultimate-subscribe-pro'); ?></li>
        </ol>
    </p>

    <h2><?php esc_html_e('My emails aren\'t including line breaks', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('This is because of the way HTML is structured. On the top left corner of the email builder,
        click on "Preformatted." This will save your line breaks.', 'ultimate-subscribe-pro'); ?>
    </p>

    <h2><?php esc_html_e("My emails aren't showing images properly.", "ultimate-subscribe-pro"); ?></h2>
    <p>
        <?php esc_html_e("All images attached to the email are hosted by your web server (thus, a third-party company 
        won't store your own images on their server, which helps protect privacy). Ensuring your website is
        accessible from the outside world will fix this.", "ultimate-subscribe-pro"
        ); ?>
    </p>

    <h2><?php esc_html_e('Help! My popup isn\'t showing.', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('There\'s a good chance your popup isn\'t showing because you subscribed to that popup.
        For the sake of user experience, once a user subscribes through a popup form, they won\'t see that form for one day.
        Clear cookies and you\'ll see the popup form again.', 'ultimate-subscribe-pro'); ?>
    </p>

    <h2><?php esc_html_e('"We\'re sorry, but subscriptions are temporarily down. Sorry!"', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('There\'s commonly two reasons why this would show up.', 'ultimate-subscribe-pro'); ?>
        <ol>
            <li><?php esc_html_e('The non-admin user was flagged as spam for attempting to register more than 4 times. The spam database is cleared daily.', 'ultimate-subscribe-pro'); ?></li>
            <li><?php esc_html_e('Your database user can not create new MySQL tables. Contact your hosting provider about this if you think this is the case', 'ultimate-subscribe-pro'); ?></li>
        </ol>
    </p>

    <h2><?php esc_html_e('My form template looks different on the admin page than my actual website', 'ultimate-subscribe-pro'); ?></h2>
    <p>
		<?php esc_html_e('This is because your theme is overriding the style for the forms. This in general isn\'t a bad thing because
        theme designers did this on purpose, so that everything on the website looks consistent. Your form should
        still look really similar. In some cases, we will override a theme\'s style if a form is not showing properly
        at all.', 'ultimate-subscribe-pro'); ?>
    </p>

    <h2><?php esc_html_e("\"This subscription category is currently down. Please contact a site administrator.\"", 'ultimate-subscribe-pro'); ?></h2>
    <p>
        <?php esc_html_e('You will receive this error if the subscription category the user subscribed to was deleted.', 'ultimate-subscribe-pro'); ?>
    </p>

    <h2><?php esc_html_e('None of these answers address my issue', 'ultimate-subscribe-pro'); ?></h2>
    <p><?php esc_html_e('Try deactivating and reactivating the plugin. If this doesn\'t work, contact us.', 'ultimate-subscribe-pro'); ?></p>

</div>