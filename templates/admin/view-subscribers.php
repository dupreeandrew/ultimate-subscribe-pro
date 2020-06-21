<?php
/*
 * Received Variables:
 * $category_name
 * $subscriber_name_email_timestamp_array
 */
?>

<div class="wrap">
    <h1><?php esc_html_e("Subscriber List", 'ultimate-subscribe-pro'); ?>: <?php echo esc_html($category_name); // $category_name is dynamic based on user input ?></h1>
    <p>
        <?php esc_html_e('Only the last 100 subscribed users are shown. Export this category as a CSV to get the entire list.', 'ultimate-subscribe-pro'); ?>
    </p>
	<table class="widefat fixed" cellspacing="0">
		<thead>
            <tr>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Name', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Email', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Time', 'ultimate-subscribe-pro'); ?></th>
            </tr>
		</thead>
		<tfoot>
            <tr>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Name', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Email', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Time', 'ultimate-subscribe-pro'); ?></th>
            </tr>
		</tfoot>

		<tbody>
		<?php
		foreach ($subscriber_name_email_timestamp_array as $subscriber_array) { ?>
			<tr class="alternate">
				<td class="column-columnname"><b><?php echo esc_html($subscriber_array['name']); ?></b></td>
				<td class="column-columnname"><b><?php echo esc_html($subscriber_array['email']); ?></b></td>
				<td class="column-columnname"><b><?php echo esc_html($subscriber_array['timestamp']); ?></b></td>
			</tr>

		<?php } ?>
		</tbody>
	</table>
    <div>
        <h3><?php esc_html_e("Subscriber Removal Tool", 'ultimate-subscribe-pro'); ?>:</h3>
        <form action="#" method="POST">
            <p>
                <label for="remove-subscriber"><?php esc_html_e('Subscriber Email:', 'ultimate-subscribe-pro'); ?></label>
                <input id="remove-subscriber" name="remove_subscriber_email" type="text" placeholder="<?php esc_html_e('account@domain.com', 'ultimate-subscribe-pro'); ?>">
            </p>
			<?php submit_button(esc_html__("Remove subscriber", 'ultimate-subscribe-pro')); ?>
        </form>
    </div>
</div>
