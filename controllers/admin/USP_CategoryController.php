<?php

class USP_CategoryController implements USP_Controller {

	public function init() {

		require_once(USP_BASE_DIR . "models/USP_Category.php");
		require_once(USP_BASE_DIR . "views/admin/USP_CategoriesView.php");
		require_once(USP_BASE_DIR . "views/admin/USP_CreateCategoryView.php");


		$categories_title = esc_html__("Categories", "ultimate-subscribe-pro");

		add_submenu_page("subscribepro",
			$categories_title,
			$categories_title,
			"manage_options",
			"subscribepro_categories",
			[$this, 'load_categories_page']);

	}

	public function load_categories_page() {

		if (isset($_GET['action'])) {

			$action = $_GET['action'];
			if ($action === "new_category") {
				USP_CreateCategoryView::renderHTML();
				return;
			}

			if ($action === "view_subscribers" && isset($_GET['id'])) {

			    $category = USP_Category::getCategory($_GET['id']);

			    if ($category !== null) {

					$this->checkDeletedSubscriber($category);

			        $category_name = $category->getName();
			        $subscriber_array = [];
			        $category->get_subscribers(function($name, $email, $unique_code, $timestamp) use (&$subscriber_array) {
			            $subscriber_array[] = [
                            "name" => $name,
                            "email" => $email,
                            "timestamp" => $timestamp
                        ];
                    }, 100);
					require_once(USP_BASE_DIR . "views/admin/USP_ViewSubscribersView.php");
					USP_ViewSubscribersView::renderHTML($category_name, $subscriber_array);
					return;
                }
            }

		}

		// Default Page, regardless of action
		$this->checkNewCategory();
		$this->checkDeletedCategory();
		$categories = USP_Category::getAllCategories();
		USP_CategoriesView::renderHTML($categories);
		return;

	}

	/**
	 * Checks to see if a new category has been created via a post request
	 */
	private function checkNewCategory() {

		if (!isset($_POST['usp_new_category_name'])) {
			return;
		}

		$category_name = sanitize_text_field($_POST['usp_new_category_name']);
		if (USP_Category::write($category_name) > 1) { ?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e('Your category was added!', 'ultimate-subscribe-pro'); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'ultimate-subscribe-pro'); ?></span>
				</button>
			</div>

			<?php return;
		}

		$msg = esc_html__("Your category has been added!", 'ultimate-subscribe-pro');
		USP_Announcer::echoNotification($msg, true);

	}

	/**
	 * Checks to see a category has been deleted via a post request
	 */
	private function checkDeletedCategory() {
		if (!isset($_GET['action']) || !isset($_GET['id'])) {
			return;
		}

		$action = $_GET['action'];
		if ($action !== "delete" || !is_numeric($_GET['id'])) {
			return;
		}

		$id = (int)$_GET['id'];

		$success_msg = esc_html__("Category was deleted.", 'ultimate-subscribe-pro');
		$fail_msg = esc_html__("Category could not be deleted", 'ultimate-subscribe-pro');

		USP_Category::delete($id)
			? USP_Announcer::echoNotification($success_msg, true)
			: USP_Announcer::echoNotification($fail_msg);

	}

	private function checkDeletedSubscriber($category) {

	    if (!isset($_POST['remove_subscriber_email'])) {
	        return;
        }

	    $subscriber_email = $_POST['remove_subscriber_email'];
	    if (!is_email($subscriber_email)) {
	        $msg = esc_html__("Please enter a valid email.", 'ultimate-subscribe-pro');
			USP_Announcer::echoNotification($msg);
	        return;
        }

	    $success = $category->force_remove_subscriber($subscriber_email);

	    if ($success) {
	        $msg = esc_html__("Subscriber was removed!", 'ultimate-subscribe-pro');
        }
	    else {
	        $msg = esc_html__("Subscriber could not be found.", 'ultimate-subscribe-pro');
        }

		USP_Announcer::echoNotification($msg, true);

    }


}