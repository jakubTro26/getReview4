<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @param WP_REST_Request $request This function accepts a rest request to process data.
 */


if(!class_exists("WiremoWooRoutes")) {
    class WiremoWooRoutes {
        public $data = array();
        public function __construct(){
            add_action('rest_api_init', array($this,'register_wiremo_routes'));
        }

        public function wiremo_update_statistics_rating($request) {

            if (isset($request['data'])) {
                $response = $request['data'];
                $siteId = esc_attr(get_option("wiremo-siteId"));
                $apiKey = esc_attr(get_option("wiremo-api-key"));
                $result = new stdClass();
                if (isset($response)) {
                    if ($response["type"] == "review") {
                        if ($siteId == $response["siteId"] && $apiKey == $response["key"]) {
                            $productId = $response["identifier"];
                            $revievResponse = $response["data"];
                            $countReview = $revievResponse["count"];
                            $ratingSum = $revievResponse["ratingSum"];
                            $ratingStar = ($ratingSum != 0) ? (($ratingSum / ($countReview * 5)) * 100) : 0;
                            $rating_sum_1 = $revievResponse["detailedData"][0]["ratingSum"];
                            $rating_sum_2 = $revievResponse["detailedData"][1]["ratingSum"];
                            $rating_sum_3 = $revievResponse["detailedData"][2]["ratingSum"];
                            $rating_sum_4 = $revievResponse["detailedData"][3]["ratingSum"];
                            $rating_sum_5 = $revievResponse["detailedData"][4]["ratingSum"];
                            $average_rating = ($ratingSum !=0) ? ($rating_sum_1+$rating_sum_2+$rating_sum_3+$rating_sum_4+$rating_sum_5)/$countReview : 0;
                            if((int)$average_rating != $average_rating) {
                                $average_rating = number_format($average_rating,2);
                            }
                            $revievTotal = json_encode($response["data"]);
                            if(is_numeric($productId)) {
                                $post_type = get_post_type($productId);
                                if($post_type == "product") {
                                    if (!add_post_meta($productId, 'wiremo-review-total', $revievTotal, true)) {
                                        update_post_meta($productId, 'wiremo-review-total', $revievTotal);
                                        $result->success = "ok";
                                    }
                                    if (!add_post_meta($productId, 'wiremo-review-average', $average_rating, true)) {
                                        update_post_meta($productId, 'wiremo-review-average', $average_rating);
                                        $result->success = "ok";
                                    }
                                    if (!add_post_meta($productId, 'wiremo-review-count', $countReview, true)) {
                                        update_post_meta($productId, 'wiremo-review-count', $countReview);
                                        $result->success = "ok";
                                    }
                                }
                            }
                        } else {
                            $result->error = "Incorrect siteId and apiKey";
                            echo wiremo_wh_log("----------Log: Routes wordpress error: ".$result->error ." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                        }
                    } else if ($response["type"] == "template") {
                        if ($siteId == $response["siteId"] && $apiKey == $response["key"]) {
                            $templateOptions = $response["data"];
                            if (isset($templateOptions["starColor"]) && isset($templateOptions["language"])) {
                                ($templateOptions["hover"] == true) ? update_option("wiremo-widget-hover", true) : update_option("wiremo-widget-hover", false);
                                (get_option("wiremo-widget-star-color")) ? update_option("wiremo-widget-star-color", $templateOptions["starColor"]) : add_option("wiremo-widget-star-color", $templateOptions["starColor"]);
                                (get_option("wiremo-widget-language")) ? update_option("wiremo-widget-language", $templateOptions["language"]) : add_option("wiremo-widget-language", $templateOptions["language"]);
                                if(!update_option("wiremo-widget-star-style",$templateOptions["starStyle"])) {
                                    add_option("wiremo-widget-star-style",$templateOptions["starStyle"]);
                                }
                                if(!update_option("wiremo-widget-text-font",$templateOptions["textFont"])) {
                                    add_option("wiremo-widget-text-font",$templateOptions["textFont"]);
                                }
                                if(!update_option("wiremo-widget-star-size",$templateOptions["starSize"])) {
                                    add_option("wiremo-widget-star-size",$templateOptions["starSize"]);
                                }
                            } else {
                                $result->error = "Object syntax error";
                                echo wiremo_wh_log("----------Log: Routes wordpress error: ".$result->error ." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                            }
                        } else {
                            $result->error = "Incorrect siteId and apiKey";
                            echo wiremo_wh_log("----------Log: Routes wordpress error: ".$result->error ." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                        }
                    } else {
                        $result->error = "You need to specified type";
                        echo wiremo_wh_log("----------Log: Routes wordpress error: ".$result->error ." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                    }
                } else {
                    $result->error = "You have an error";
                    echo wiremo_wh_log("----------Log: Routes wordpress error: ".$result->error ." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                }

            }
            return rest_ensure_response($result);
        }

        public function wiremo_convert_identifiers($identifiers,$type) {
            if(is_array($identifiers) && $type == "identifier") {
                for($i=0;$i<count($identifiers);$i++) {
                    $item = new StdClass();
                    $url = get_permalink($identifiers[$i]);
 
                  if($url != false) {
                        $path = str_replace(home_url(),"",$url);
                        $title = get_the_title($identifiers[$i]);
                        $path_key = (string)$identifiers[$i];
                        $item->$path_key = new stdClass();
                        $item->$path_key->identifier = $identifiers[$i];
                        $item->$path_key->path = $path;
                        $item->$path_key->title = $title;
                        $item->$path_key->url = $url;
                        $this->data[] = $item;
                    }
                }
            }
            else if(is_array($identifiers) && $type == "url") {
                for($i=0;$i<count($identifiers);$i++) {
                    $item = new StdClass();
                    $id = url_to_postid(home_url()."/".$identifiers[$i]);
                    if($id != 0) {
                        $post_type = get_post_type($id);
                        $url = get_permalink($id);
                        $path = str_replace(home_url(),"",$url);
                        $title = get_the_title($id);
                        $path_key = (string)$identifiers[$i];
                        $item->$path_key = new StdClass();
                        if($post_type != "product") {
                            $item->$path_key->identifier = $path;
                        }
                        else {
                            $item->$path_key->identifier = (string)$id;
                        }
                        $item->$path_key->path = $path;
                        $item->$path_key->title = $title;
                        $item->$path_key->url = $url;
                        $this->data[] = $item;
                    }
                }
            }

        }

        public function wiremo_import_identifiers( $request) {
	    $request = $request->get_params();
            if(isset($request['response'])) {
                $result = array();
                $response = $request['response'][0];
                if(isset($response) && !empty($response)) {
                    $api_key = get_option("wiremo-api-key");
                    if($api_key == $request['apiKey']) {
                        $identifiers = array();
                        $urls = array();

                            if($response['type'] == "identifier") {
                                $identifiers = $response["data"];
                            }
                            else {
                                $urls = $response["data"];
                            }

                        if(count($identifiers) > 0) {
                            $this->wiremo_convert_identifiers($identifiers,"identifier");
                        }
                        if(count($urls) > 0) {
                            $this->wiremo_convert_identifiers($urls,"url");
                        }
                        $result = $this->data;
                    }
                    else {
                        echo wiremo_wh_log("----------Log: Routes wordpress Incorrect apikey ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
                    }
                }
            }
            return rest_ensure_response($result);
        }


        public function register_wiremo_routes() {

            // Here we are registering our route for a received requests from wiremo.
            register_rest_route('wiremo/v1', '/hook', array(
                array(
                    // By using this constant we ensure that when the WP_REST_Server changes, our create endpoints will work as intended.
                    'methods' => WP_REST_Server::CREATABLE,
                    // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
                    'callback' => array($this,'wiremo_update_statistics_rating'),
                    'permission_callback' => '__return_true'
                ),
            ));
            register_rest_route('wiremo/v1', '/import', array(
                array(
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => array($this,'wiremo_import_identifiers'),
                    'permission_callback' => '__return_true'
                ),
            ));
        }


    }
    // initialise class WiremoWooRoutes
    add_action( 'init', 'wiremo_woo_init_routes' );
    if(!function_exists("wiremo_woo_init_routes")) {
        function wiremo_woo_init_routes() {
            global $wiremo_routes;
            $wiremo_routes = new WiremoWooRoutes();
        }
    }
}
