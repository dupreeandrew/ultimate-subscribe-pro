<div class="wrap">
	<h1><?php esc_html_e('Create a category', 'ultimate-subscribe-pro'); ?></h1>
	<form method="post" action="<?php echo esc_url(USP_URLGetter::getAdminCategoryPage()); ?>">
		<p>
			<label for="usp_new_category_name"><?php esc_html_e('Category Name', 'ultimate-subscribe-pro'); ?></label>
			<input type="text" id="usp_new_category_name" name="usp_new_category_name" placeholder="<?php esc_html_e('ex: Daily Newsletter', 'ultimate-subscribe-pro'); ?>">
		</p>
		<input type="submit" value="<?php esc_html_e('Submit new category', 'ultimate-subscribe-pro'); ?>" class="button button-primary">
	</form>
</div>