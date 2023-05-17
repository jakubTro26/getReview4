<?php
/*

**************************************************************************

Plugin Name: Wiremo – Product Reviews for WooCommerce
Plugin URI: https://wiremo.co/
Description: Wiremo is a convenient customer review plugin aimed to help consumer-centric teams improve their products and make their team more effective by listening to their most substantial asset – customers.
Version: 1.4.97
Author: Wiremo
Author URI: https://wiremo.co
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wiremo-widget
WooCommerce tested up to: 6.7.0

**************************************************************************
 Copyright (C) 2016-2022 Wiremo

Wiremo – Product Reviews for WooCommerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Wiremo – Product Reviews for WooCommerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function appsero_init_tracker_woo_reviews_by_wiremo() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '172c513c-cf7c-46c4-b343-43839a926852', 'Wiremo & Product Reviews for WooCommerce', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_woo_reviews_by_wiremo();



define("WRPW_URLAPP", "https://wapi.wiremo.co");
define("WRPW_URLWIDGET", "https://wapi.wiremo.co/v2/script");

define("WRPW_ORDER_LIMIT",400);
define("WRPW_ORDER_PER_PAGE",100);
define("WRPW_LIMIT_REQ",50);
define("WRPW_PLUGIN_DIR", dirname(__FILE__).'/' );

include dirname( __FILE__ ).'/includes/logs.php';
include dirname( __FILE__ ).'/includes/check-site.php';
include dirname( __FILE__ ).'/includes/admin-ajax.php';

/* Load translations */
function wiremo_widget_load_plugin_textdomain()
{
    load_plugin_textdomain('wiremo-widget');
}

add_action('plugins_loaded', 'wiremo_widget_load_plugin_textdomain');
function wiremo_admin_fonts() {
    wp_enqueue_style('admin-fonts', plugins_url('/css/admin-fonts.css', __FILE__), '1.0', 'screen');
}
add_action( 'admin_enqueue_scripts','wiremo_admin_fonts');

if(is_admin()) {
    if(isset($_GET["page"]) && !empty($_GET["page"])) {
        if (sanitize_text_field($_GET["page"]) == "wiremo-widget"):
            function wiremo_admin_style()
            {
                wp_enqueue_style('font-awesome', plugins_url('/css/font-awesome.min.css', __FILE__), array(), '1.0', 'screen');
                wp_enqueue_style('bootstrap-style', plugins_url('/css/bootstrap.min.css', __FILE__), array(), '4.4.1', 'screen');
                wp_enqueue_style('wiremo-admin-jquery-ui', plugins_url('/css/admin-jquery-ui.css', __FILE__), array(), '1.0', 'screen');
                wp_enqueue_style('admin-style', plugins_url('/css/admin-style.css', __FILE__), array(), '1.4.35', 'screen');
                wp_enqueue_style('star-style', plugins_url('/css/star-style.css', __FILE__), array(), '1.4.35', 'screen');

                wp_enqueue_script('popper-js', plugins_url('/js/popper.min.js', __FILE__), array(), '1.12.9', true);
                wp_enqueue_script('bootstrap-js', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery', 'popper-js'), '4.4.1', true);
                wp_enqueue_script('noconflict-js', plugins_url('/js/noconflict.js', __FILE__), array('jquery'), '1.1', true);
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script('jquery-ui-progressbar');
                wp_enqueue_script('script-admin-js', plugins_url('/js/admin.js', __FILE__), array('jquery'), '1.4.35', true);
            }
            add_action('admin_enqueue_scripts', 'wiremo_admin_style');
        endif;
    }
}

function wiremo_widget_settings_page()
{
    if (current_user_can('administrator')) {
        ?>
        <div class="wrap wiremo-full-container">
            <div class="wiremo-container">
            <div style="display:none" id="nonceWrpw_reset_old_identifiers"><?php echo wp_create_nonce('wrpw_reset_old_identifiers');?></div>
            <div style="display:none" id="nonceImportWiremoStatistics"><?php echo wp_create_nonce('importWiremoStatistics');?></div>
            <div style="display:none" id="nonceImportReviewsToWiremo"><?php echo wp_create_nonce('importReviewsToWiremo');?></div>
            <div style="display:none" id="nonceWiremoAddApiKey"><?php echo wp_create_nonce('wiremoAddApiKey');?></div>
            <div style="display:none" id="nonceWiremoAddRegisterHook"><?php echo wp_create_nonce('wiremoAddRegisterHook');?></div>
            <div style="display:none" id="nonceWiremoAuth"><?php echo wp_create_nonce('wiremoAuth');?></div>
            <div style="display:none" id="nonceWiremoAutoRegister"><?php echo wp_create_nonce('wiremoAutoRegister');?></div>
            <div style="display:none" id="nonceWiremoGetSiteId"><?php echo wp_create_nonce('wiremoGetSiteId');?></div>
            <div style="display:none" id="nonceWiremoNoValidate"><?php echo wp_create_nonce('wiremoNoValidate');?></div>
            <div style="display:none" id="nonceWiremoValidateSite"><?php echo wp_create_nonce('wiremoValidateSite');?></div>
            <div style="display:none" id="nonceWiremo_save_campaign_information"><?php echo wp_create_nonce('wiremo_save_campaign_information');?></div>
            <div style="display:none" id="nonceWiremo_send_completed_orders"><?php echo wp_create_nonce('wiremo_send_completed_orders');?></div>
                <h1><?php echo __("Wiremo – Product Reviews for WooCommerce","wiremo-widget"); ?></h1>
                <?php
                $siteId = esc_attr(get_option("wiremo-siteId"));
                $apiKey = esc_attr(get_option("wiremo-api-key"));
                $registerHooks = esc_attr(get_option("wiremo-register-hooks"));
                if (isset($siteId) && !empty($siteId) && isset($apiKey) && !empty($apiKey) && isset($registerHooks) && !empty($registerHooks)) {
                    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                        include dirname( __FILE__ ).'/includes/user-settings.php';
                    } else {
                        include dirname( __FILE__ ).'/templates/inactive-plugins.php';
                    }
                } else {
                    include dirname( __FILE__ ).'/includes/user-connect.php';
                }
                ?>
            </div>
        </div>
        <?php
    }
}

function add_wiremo_menu_item()
{
    if (current_user_can('administrator')) {
        add_menu_page("Wiremo", "Wiremo", "moderate_comments", "wiremo-widget", "wiremo_widget_settings_page","dashicons-admin-generic");
    }
}

add_action("admin_menu", "add_wiremo_menu_item");

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wiremo_action_links' );

function wiremo_action_links( $links ) {
    $action_links = array(
        'settings' => '<a href="' . admin_url( 'admin.php?page=wiremo-widget' ) . '" aria-label="' . esc_attr__( 'View Wiremo settings', 'wiremo-widget' ) . '">' . esc_html__( 'Settings', 'wiremo-widget' ) . '</a>',
    );
    return array_merge( $action_links, $links );
}

$siteId = esc_attr(get_option("wiremo-siteId"));
$apiKey = esc_attr(get_option("wiremo-api-key"));
$registerHooks = esc_attr(get_option("wiremo-register-hooks"));

if (isset($siteId) && !empty($siteId) && isset($apiKey) && !empty($apiKey) && isset($registerHooks) && !empty($registerHooks)) {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        include dirname( __FILE__ ).'/config.php';
        include dirname( __FILE__ ).'/routes.php';
    }
}
