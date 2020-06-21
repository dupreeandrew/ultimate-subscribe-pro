<?php
/*
 * Received Variables:
 * @var Category[] $categories array of Category objects.
 */
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Categories', 'ultimate-subscribe-pro'); ?></h1>
    <a href="<?php echo USP_URLGetter::getAdminCategoryPage() . "&action=new_category"; ?>" class="page-title-action">
		<?php esc_html_e('Create Category', 'ultimate-subscribe-pro'); ?>
    </a>
    <p>
		<?php esc_html_e('Visitors can subscribe to any categories defined here through a subscription button you may create via the Form Creator.', 'ultimate-subscribe-pro');
		?>
    </p>
    <br />
    <table class="widefat fixed" cellspacing="0">
        <thead>
        <tr>
            <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Category Name', 'ultimate-subscribe-pro'); ?></th>
            <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Actions', 'ultimate-subscribe-pro'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Category Name', 'ultimate-subscribe-pro'); ?></th>
            <th class="manage-column column-columnname" scope="col"><?php esc_html_e('Actions', 'ultimate-subscribe-pro'); ?></th>
        </tr>
        </tfoot>

        <tbody>

        <?php


		foreach ($categories as $category) { ?>
			<tr class="alternate">


                <td class="column-columnname"><b><?php echo sanitize_text_field($category->getName()); ?></b></td>
                <td class="column-columnname">
                    <span><a href="<?php echo USP_BASE_URL . "CSVExporter.php?id=" . (int)$category->getId();?>"><?php esc_html_e('Export to CSV', 'ultimate-subscribe-pro'); ?></a> |</span>
                    <span>
                        <?php $warning_message = esc_html__('Are you sure you want to delete this category? You will not be able to recover the subscriber list for this category. Form buttons that use this category will no longer work.', 'ultimate-subscribe-pro'); ?>
                        <a href="<?php echo USP_URLGetter::getAdminCategoryPage() . '&action=delete' . '&id=' . (int)$category->getId(); ?>" onclick="return confirm('<?php echo esc_html($warning_message) ?>');">
                            <?php esc_html_e('Delete', 'ultimate-subscribe-pro'); ?> |
                        </a>
                    </span>
                    <span>
                        <a href="<?php echo USP_URLGetter::getAdminCategoryPage() . '&action=view_subscribers' . '&id=' . (int)$category->getId(); ?>">
                            <?php esc_html_e('View Subscribers', 'ultimate-subscribe-pro'); ?>
                        </a>
                    </span>
                </td>
            </tr>
		<?php } ?>




        </tbody>
    </table>
</div>