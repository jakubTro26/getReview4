<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WiremoWpmlData {
    
    public function wrpw_show_wpml_option() {
        if (current_user_can('administrator')) {
            ?>
            <input  name="wrpw_wpml_option" id="wrpw_wpml_option" type="checkbox"
                                                                                          value="1" <?php checked('1', get_option('wrpw_wpml_option')); ?> />
            <?php
        }
    }
    
    public function wrpw_display_wpml_option() {
        if (current_user_can('administrator')) {
            add_settings_field("wrpw_wpml_option", __("Merge all reviews in all languages (WPML)","wiremo-widget"), array($this,'wrpw_show_wpml_option'), "theme-options", "section");
            register_setting("section", "wrpw_wpml_option");
        }
    }
    
    public function wrpw_get_main_product_id($product_id) {
        if(get_option("wrpw_wpml_option")) {
            global $sitepress;
            $wrpw_default_wpml_lang = $sitepress->get_default_language();
            $wrpw_main_product = icl_object_id($product_id, 'product', false, $wrpw_default_wpml_lang);
            if(empty($wrpw_main_product)) {
                $wrpw_languages = icl_get_languages('skip_missing=0&orderby=code');
                $wrpw_keys_languages = array_keys($wrpw_languages);
                if(count($wrpw_keys_languages) > 0) {
                    for($i=0;$i<count($wrpw_keys_languages);$i++) {
                        $wrpw_main_product = icl_object_id($product_id, 'product', false, $wrpw_keys_languages[$i]);
                        if(!empty($wrpw_main_product)) {
                            if(get_post_meta($wrpw_main_product, 'wiremo-review-total')) {
                                break;
                            }
                        }
                    }
                }
            }
        } else {
            $wrpw_main_product = $product_id;
        }
        return $wrpw_main_product;
    }

    public function wrpw_migrate_statistic($product_id) {
        if(get_option("wrpw_wpml_option")) {
            $wrpw_post_type = get_post_type($product_id);
            if($wrpw_post_type == "product") {
                global $sitepress;
                $wrpw_default_wpml_lang = $sitepress->get_default_language();
                $wrpw_default_product_id = icl_object_id($product_id, 'product', false, $wrpw_default_wpml_lang);
                if($wrpw_default_product_id == $product_id) {
                    $wrpw_default_prod_lang = $wrpw_default_wpml_lang;
                    $wrpw_languages = icl_get_languages('skip_missing=0&orderby=code');
                    $wrpw_keys_languages = array_keys($wrpw_languages);
                    if(count($wrpw_keys_languages) > 0) {
                        for($i=0;$i<count($wrpw_keys_languages);$i++) {
                            if($wrpw_keys_languages[$i] != $wrpw_default_wpml_lang) {
                                $wrpw_default_prod_lang = $wrpw_keys_languages[$i];
                                $wrpw_default_product_id = icl_object_id($product_id, 'product', false, $wrpw_default_prod_lang);
                                if(!empty($wrpw_default_product_id)) {
                                    break;
                                }
                            }
                        }
                    }
                    $wrpw_main_product_id = icl_object_id($product_id, 'product', false, $wrpw_default_wpml_lang);
                    $wrpw_review_total = get_post_meta($wrpw_main_product_id, "wiremo-review-total", true);
                    $wrpw_review_average = get_post_meta($wrpw_main_product_id, "wiremo-review-average", true);
                    $wrpw_review_count = get_post_meta($wrpw_main_product_id, "wiremo-review-count", true);
                    if (!add_post_meta($wrpw_default_product_id, 'wiremo-review-total', $wrpw_review_total, true)) {
                        update_post_meta($wrpw_default_product_id, 'wiremo-review-total', $wrpw_review_total);
                    }
                    if (!add_post_meta($wrpw_default_product_id, 'wiremo-review-average', $wrpw_review_average, true)) {
                        update_post_meta($wrpw_default_product_id, 'wiremo-review-average', $wrpw_review_average);
                    }
                    if (!add_post_meta($wrpw_default_product_id, 'wiremo-review-count', $wrpw_review_count, true)) {
                        update_post_meta($wrpw_default_product_id, 'wiremo-review-count', $wrpw_review_count);
                    }
                    $apikey = get_option("wiremo-api-key");
                    $url = get_permalink($wrpw_default_product_id);
                    $path = str_replace(home_url(),"",$url);
                    $post_fields = array();
                    $post_fields["apiKey"] = $apikey;
                    $post_fields["identifier"] = "$wrpw_main_product_id";
                    $post_fields["newIdentifier"] = "$wrpw_default_product_id";
                    $post_fields["path"] = $path;
                    $post_fields["url"] = $url;
                    $urlApi = WRPW_URLAPP."/v1/ecommerce/updateIdentifier";
                    $response = wp_remote_post( $urlApi, array(
                            'method' => 'POST',
                            'body' => $post_fields
                        )
                    );
                    if ( is_wp_error( $response ) ) {
                        $wrpw_error_message = $response->get_error_message();
                        return $wrpw_error_message;
                    }
                }
            }
        }
    }
}