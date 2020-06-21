<?php
/**
 * Received variables:
 * $all_form_details -> array of form arrays, containing "name" & "id" & "shortcode" keys.
 *
 * <tr class="no-items"><td colspan="3" class="colspanchange">No mailing list subscribers have been added.</td></tr>';
 *
 * Table heading:
 * Form Name | shortcode | edit link
 */
?>

<div class="wrap">


    <h1></h1>
    <h1 class="wp-heading-inline"><?php esc_html_e('View Forms', 'ultimate-subscribe-pro'); ?></h1>
    <a href="<?php echo USP_URLGetter::getCreateFormPage()?>" class="page-title-action">
        <?php esc_html_e('Create Form', 'ultimate-subscribe-pro'); ?>
    </a>
    <p>
		<?php esc_html_e('Here you can view the subscription forms that you have made.', 'ultimate-subscribe-pro'); ?>
    </p>

	<?php if ($display_thank_you_message) { ?>
        <div class="notice notice-info">
            <p>
                <strong><em><?php esc_html_e('Welcome!', 'ultimate-subscribe-pro'); ?></em></strong>
                <br>
				<?php esc_html_e('Thank you for installing Ultimate Subscribe Pro! If you encounter any questions or problems, please feel free to request for help!', 'ultimate-subscribe-pro'); ?>
                <br>
            </p>
        </div>
	<?php } ?>

    <br />
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Form Name', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Shortcode', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Actions', 'ultimate-subscribe-pro'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Form Name', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Shortcode', 'ultimate-subscribe-pro'); ?></th>
                <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Actions', 'ultimate-subscribe-pro'); ?></th>
            </tr>
        </tfoot>

        <tbody>
        <?php
        foreach ($all_form_details as $form_details) { ?>
            <tr class="alternate">
                <td class="column-columnname"><b><?php echo esc_html($form_details['name']) ?></b></td>
                <td class="column-columnname"><?php echo esc_html($form_details['shortcode']) ?></td>
                <td class="column-columnname">
                    <?php $form_id = (int)$form_details['id']; ?>
                    <span><a href="<?php echo USP_URLGetter::getCreateFormPage() . "&id=$form_id"?>"><?php esc_html_e('Edit', 'ultimate-subscribe-pro'); ?></a> |</span>
                    <span><a href="<?php echo USP_URLGetter::getViewFormsPage() . "&action=delete&id=$form_id"?>"><?php esc_html_e('Delete', 'ultimate-subscribe-pro'); ?></a></span>
                </td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
</div>