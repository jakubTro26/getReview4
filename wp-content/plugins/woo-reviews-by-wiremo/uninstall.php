<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!defined("WP_UNINSTALL_PLUGIN"))
    exit();
global $wpdb;
$wrpw_api_key = get_option("wiremo-api-key");
if($wrpw_api_key) {
    $wrpw_url_api = "https://wapi.wiremo.co";
    $wrpw_url_delete = $wrpw_url_api."/v1/ecommerce/uninstall";
    $wrpw_post_fields = array();
    $wrpw_post_fields["apiKey"] = $wrpw_api_key;
    $wrpw_post_fields["type"] = "woocommerce";
    $wrpw_response = wp_remote_post( $wrpw_url_delete, array(
            'method' => 'POST',
            'body' => $wrpw_post_fields
        )
    );

    if ( is_wp_error( $wrpw_response ) ) {
        $wrpw_error_message = $wrpw_response->get_error_message();
        return $wrpw_error_message;
    }
}
(get_option("wiremo-siteId")) ? delete_option("wiremo-siteId") : "";
(get_option("wiremo-api-key")) ? delete_option("wiremo-api-key") : "";
(get_option("wiremo-register-hooks")) ? delete_option("wiremo-register-hooks") : "";
(get_option("wiremo-display-import")) ? delete_option("wiremo-display-import") : "";
(get_option("wiremo_disable_woo")) ? delete_option("wiremo_disable_woo") : "";
(get_option("wiremo_widget_display")) ? delete_option("wiremo_widget_display") : "";
(get_option("wiremo_generate_schema")) ? delete_option("wiremo_generate_schema") : "";
(get_option("wiremo_disable_woo")) ? delete_option("wiremo_disable_woo") : "";
(get_option("wiremo_custom_tab_name")) ? delete_option("wiremo_custom_tab_name") : "";
(get_option("wiremo-widget-star-color")) ? delete_option("wiremo-widget-star-color") : "";
(get_option("wiremo-widget-star-style")) ? delete_option("wiremo-widget-star-style") : "";
(get_option("wiremo-widget-star-size")) ? delete_option("wiremo-widget-star-size") : "";
(get_option("wiremo-widget-text-font")) ? delete_option("wiremo-widget-text-font") : "";
(get_option("wiremo-widget-language")) ? delete_option("wiremo-widget-language") : "";
(get_option("wiremo-widget-hover")) ? delete_option("wiremo-widget-hover") : "";
(get_option("wiremo_widget_location")) ? delete_option("wiremo_widget_location") : "";
(get_option("wiremo_hide_mini_widget_cat")) ? delete_option("wiremo_hide_mini_widget_cat") : "";
(get_option("wiremo_hide_mini_widget_prod")) ? delete_option("wiremo_hide_mini_widget_prod") : "";
(get_option("wiremo_hide_mini_widget")) ? delete_option("wiremo_hide_mini_widget") : "";
(get_option("wiremo_related_custom_text")) ? delete_option("wiremo_related_custom_text") : "";
(get_option("wiremo_related_products_sort")) ? delete_option("wiremo_related_products_sort") : "";
(get_option("wiremo_automated_authentification")) ? delete_option("wiremo_automated_authentification") : "";
(get_option("wiremo_custom_text_reviews")) ? delete_option("wiremo_custom_text_reviews") : "";
(get_option("wiremo_hide_mini_widget_home")) ? delete_option("wiremo_hide_mini_widget_home") : "";
(get_option("wiremo_show_custom_text_related")) ? delete_option("wiremo_show_custom_text_related") : "";
(get_option("wiremo_automated_review_request")) ? delete_option("wiremo_automated_review_request") : "";
(get_option("wiremo_email_template")) ? delete_option("wiremo_email_template") : "";
(get_option("wiremo_email_template_name")) ? delete_option("wiremo_email_template_name") : "";
(get_option("wiremo_manual_datetime_start")) ? delete_option("wiremo_manual_datetime_start") : "";
(get_option("wiremo_manual_datetime_end")) ? delete_option("wiremo_manual_datetime_end") : "";
(get_option("wiremo_manual_emails_day")) ? delete_option("wiremo_manual_emails_day") : "";
(get_option("wiremo_manual_email_template")) ? delete_option("wiremo_manual_email_template") : "";
(get_option("wiremo_manual_email_template_name")) ? delete_option("wiremo_manual_email_template_name") : "";
(get_option("wrpw_wpml_option")) ? delete_option("wrpw_wpml_option") : "";
if (get_option("wiremo_total_campaigns")) {
    $wiremo_total_campaigns = json_decode(get_option("wiremo_total_campaigns"));
    if ($wiremo_total_campaigns->count > 0) {
        for ($i = 1; $i <= $wiremo_total_campaigns->count; $i++) {
            (get_option("wiremo_campaigns_" . $i)) ? delete_option("wiremo_campaigns_" . $i) : "";
        }
    }
}
(get_option("wiremo_total_campaigns")) ? delete_option("wiremo_total_campaigns") : "";
$wpdb->query('DELETE FROM `'.$wpdb->prefix.'postmeta` WHERE `meta_key`="wiremo-review-total" OR `meta_key`="wiremo-review-average" OR `meta_key`="wiremo-review-count"');
