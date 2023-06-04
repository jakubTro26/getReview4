<?php

namespace GetReview;

class Admin {
	public function __construct() {
		add_action('admin_menu', [$this, 'adminMenuAdd']);
		add_action('admin_head', [$this, 'adminCustomCSS']);
	}

	public function adminMenuAdd() {
		add_menu_page(
			'GetReview',
			'GetReview',
			'manage_options',
			'getreview',
			[$this, 'adminMenuContent'],
			plugin_dir_url( __FILE__ ).'gr-logo.svg',
			999
		);
	}

	public function adminMenuContent() {
		include_once 'includes/admin.template.php';
	}

	public function adminCustomCSS() {
		echo '<style>
		#adminmenu li.toplevel_page_getreview .wp-menu-image img { height: 25px; padding: 5px; }
		.toplevel_page_getreview .notice ul { list-style: disc; padding-left: 2em; }
		</style>';
	}
}
