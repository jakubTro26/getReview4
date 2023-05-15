<?php
/*
Plugin Name: GetReview
Plugin URI: https://getreview.pl
Description: Collect reviews from customers who made purchases in the store! Reward them for opinions with a photo. Show reviews on product page.
Version: 2.0
Author: Refericon
Author URI: https://refericon.pl
Text Domain: getreview
*/

// Exit if file accessed directly
if (!defined('ABSPATH')) exit;

define('GETREVIEW_TEXT_DOMAIN', 'getreview');
define('GETREVIEW_GUID_KEY', 'getreview_guid');
define('GETREVIEW_TOKEN_KEY', 'getreview_sitekey');
define('GETREVIEW_CHECKBOX_KEY', 'getreview_opinion_add');
define('GETREVIEW_WEBHOOK_URL', 'https://app.getreview.pl/webhook/woocommerce/');
define('GETREVIEW_INSTALL_TYPE', 'getreview_install_type');
define('GETREVIEW_CHECKBOX_ENABLED', 'getreview_checkbox_enabled');
define('GETREVIEW_CHECKBOX_TEXT', 'getreview_checkbox_text');

add_action('init', function () {
	load_plugin_textdomain(GETREVIEW_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)).'/languages');
});

add_action('plugins_loaded', function () {
	require_once plugin_dir_path( __FILE__ ).'gr-connect.php';
	require_once plugin_dir_path( __FILE__ ).'gr-admin.php';

	new GetReview\Connect();
	new GetReview\Admin();
});
