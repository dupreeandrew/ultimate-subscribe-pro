<?php

    /*
     * Received Variables:
     * $category_data -> (json string) array of categories, containing keys "id" and "name". Can return "";
     * $registered_templates -> (array) RegisteredTemplate array
     * $form -> (@Nullable Form) form object. Will be always null, unless form is being edited
     */

	$fake_form = true;

	/*
	 * Force user to change skin category
	 * Force user to click add subscription button, and prefill fields
	 */


	wp_enqueue_script('create-form-realtime-update', USP_BASE_URL . "js/create-form.js", ["jquery"]);

	$usp_editor_data = [];
	if ($form !== null) {
	    $usp_editor_data["skin_name"] = $form->get_skin_name();

	    $subscription_buttons = $form->get_subscription_buttons();
	    $subscription_buttons_data_array = [];
	    foreach ($subscription_buttons as $subscription_button) {
	        $subscription_buttons_data_array[] = [
	                "text" => esc_html($subscription_button->getText()),
                    "category_id" => (int)$subscription_button->getCategoryId()
            ];
        }
	    $usp_editor_data["subscription_buttons"] = $subscription_buttons_data_array;

		$is_popup = ($form->get_popup_time() >= 0);

    }
	else {
	    $is_popup = false;
    }

	wp_localize_script('create-form-realtime-update', "usp_editor_data", $usp_editor_data);

	$button_text_string = esc_html__("Button Text", 'ultimate-subscribe-pro');
	wp_localize_script('create-form-realtime-update', "button_text_string", [
	        "text" => $button_text_string
    ]);



	require_once(USP_BASE_DIR . 'models/USP_Category.php');
    $category_data = json_encode(json_decode($category_data, true));

?>

<div class="wrap">

	<div id="col-container">
		<div id="col-left">
			<div class="col-wrap">
				<div class="inside">

					<h1><?php esc_html_e('Subscription Form Designer', 'ultimate-subscribe-pro'); ?></h1>

                    <p>
						<?php esc_html_e('Welcome your new form designer! All subscription forms utilize responsive design.
                        Check out real-time changes to your right side!', 'ultimate-subscribe-pro'); ?>
                    </p>

                    <hr>

					<form method="post">

                        <!-- Popup -->
                        <h3><?php esc_html_e('Popup Configuration', 'ultimate-subscribe-pro'); ?></h3>
                        <p>
							<?php esc_html_e('When a form is configured to popup, the form will appear when a visitor visits the page
                            with this form\'s shortcode.', 'ultimate-subscribe-pro'); ?>
                        </p>
                        <p>
                            <input id="is_popup" name="is_popup" type="checkbox" value="checked"<?php if ($is_popup) echo " checked"; ?>>
                            <label for="is_popup"><?php esc_html_e('Is this a popup form?', 'ultimate-subscribe-pro'); ?></label>
                        </p>

                        <div id="is_popup_frequency_container"<?php if (!$is_popup) echo ' style="display: none"'; ?>>
                            <p>
                                <label for="is_popup_time"><?php esc_html_e('How many seconds between visits should this popup?', 'ultimate-subscribe-pro'); ?></label>
                                <?php $popup_time = ($form === null) ? 0 : $form->get_popup_time(); ?>
                                <input id="is_popup_time" name="is_popup_time" type="number"<?php if ($is_popup) echo " value=" . (int)$popup_time ?>>
                            <p><strong><?php esc_html_e('Tip: You can set it to 0 for the form to always popup.', 'ultimate-subscribe-pro'); ?></strong></p>
                        </div>

                        <!-- Form Details -->

                        <hr>

                        <h3><?php esc_html_e('Form Details', 'ultimate-subscribe-pro'); ?></h3>

						<p>
							<label for="form-title"><?php esc_html_e('Form Title', 'ultimate-subscribe-pro'); ?></label>
							<br />
							<input id="form-title" name="form_title" type="text" <?php if ($form !== null) echo 'value="' . esc_html($form->get_name()) . '"'; ?> required>
						</p>

						<p>
							<label for="form-body"><?php esc_html_e('Form Body', 'ultimate-subscribe-pro'); ?></label>
							<br />
							<textarea id="form-body" name="form_body" rows="4" style="width: 80%"><?php if ($form !== null) echo esc_html($form->get_body()); ?></textarea>
						</p>

                        <hr>

                        <h3><?php esc_html_e('Subscription Buttons', 'ultimate-subscribe-pro'); ?></h3>
                        <p>
							<?php

                            esc_html_e('Each subscription button has a category & text.
                            When a user clicks on that button, their contact information will be added to
                            the category you just assigned to it. ', 'ultimate-subscribe-pro');

							$url = admin_url('admin.php?page=subscribepro_categories');
							$allowed_html = ['a' => ["href" => []]];
							printf(wp_kses(__('Click <a href="%s">here</a> to manage categories.', 'ultimate-subscribe-pro'), $allowed_html), $url);

							?>


                        </p>

                        <?php

                        if (empty($category_data)) {
                            $category_page_url = admin_url('admin.php?page=subscribepro_categories');
                            $create_a_category = esc_html__('Create a category', 'ultimate-subscribe-pro');
                            echo "<a class=\"button button-primary\" href=\"$category_page_url\">$create_a_category</a>";
                        }
                        else {
                            // $category_data is properly validated through json_encode(json_decode).
                            echo '<div id="usp-category-data" style="display: none">' . $category_data . '</div>';
                            ?>

                            <table>
                                <thead>
                                    <tr>
                                        <td><?php esc_html_e('Category', 'ultimate-subscribe-pro'); ?></td>
                                        <td><?php echo esc_html($button_text_string); ?></td>
                                    </tr>
                                </thead>
                                <tbody id="usp-tbody-category-text">
                                </tbody>
                            </table>
                            <button id="usp-btn-add-row"><?php esc_html_e('Add Button', 'ultimate-subscribe-pro'); ?></button>
                            <button id="usp-btn-delete-row" ><?php esc_html_e('Delete Last Button', 'ultimate-subscribe-pro'); ?></button>

                        <?php } ?>

                        <hr>

                        <!-- Form Styling -->
                        <h3><?php esc_html_e('Form Styling', 'ultimate-subscribe-pro'); ?></h3>
						<p>
							<label for="form-skin"><?php esc_html_e('Form Skin', 'ultimate-subscribe-pro'); ?></label>
							<select id="form-skin" name="form_skin">
                                <?php
                                foreach ($registered_templates as $template_name => $template_file) {
                                    $template_name = esc_html($template_name);
                                    echo "<option value='$template_name'>$template_name</option>";
                                }
                                ?>
							</select>
						</p>

                        <hr>

                        <h3><?php esc_html_e("You're done!", 'ultimate-subscribe-pro'); ?></h3>

                        <?php
                        if (empty($category_data)) {
                            $must_create_category_msg = esc_html__('You must create a category before creating a form.', 'ultimate-subscribe-pro');
							echo "<p>$must_create_category_msg</p>";
                        }
                        else {
                            $create_form_msg = esc_html__('Create Form', 'ultimate-subscribe-pro');
                            echo "<input type=\"submit\" value=\"$create_form_msg\" />";
                        }
                        ?>

					</form>


				</div>
			</div>
		</div>

		<div id="col-right">
			<div class="col-wrap">
				<div class="inside" style="max-width: 700px">
					<h1><?php esc_html_e("Responsive Design: Real Time Visualizer", "ultimate-subscribe-pro"); ?></h1>

                    <?php

					if ($form !== null) {
						$title = esc_html($form->get_name());
						$body = esc_html($form->get_body());
						$is_popup = ($form->get_popup_time() >= 0);
					}
					else {
						$title = esc_html__("Don't miss updates from us!", 'ultimate-subscribe-pro');
						$body = esc_html__("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam", 'ultimate-subscribe-pro');
						$is_popup = false;
					}

                    $first_file = true;
                    foreach ($registered_templates as $template_name => $template_file) {

						$template_name = esc_html($template_name);

                        if ($first_file) {
							echo "<div id='form-skin-$template_name'>";
							$first_file = false;
						}
                        else {
							echo "<div id='form-skin-$template_name' style='display: none'>";
                        }

                        $parent_container_id = strtolower("usp-skin-$template_name-wrapper");

                        // $template_file[] is hard-coded through USP_RegisteredSkins.php
                        include($template_file);
                        echo '</div>';

                    }
                    ?>

				</div>


			</div>
		</div>
	</div>
</div>