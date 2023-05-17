<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
function sanitize_text_or_array_field($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}
if (is_admin()) {
    function wiremoAuth()
    {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoAuth')){
            wp_die('Request not trusted');
        }
        include 'oauth-user.php';
        die();
    }

    function wiremoAutoRegister()
    {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoAutoRegister')){
            wp_die('Request not trusted');
        }
        $host_url = home_url();
        $error_empty_host = 'We already have this email registered, please, go to your Wiremo Dashboard -> Install Wiremo and enter <strong>' . $host_url . '</strong> in Step 1 then click Next.<br>
        Then come back and click on “Connect your Wiremo account” and the plugin will do the magic!<br>
        If you have any issue just <a target="_blank" href="https://wiremo.co/contact-us/">contact our support team</a>.';
        $error_user_exists = 'We already have this domain registered under another account.<br>
        Please, use the already created account to connect Wiremo plugin to your Dashboard.<br>
        If you have any issue just <a target="_blank" href="https://wiremo.co/contact-us/">contact our support team</a>.';
        if (isset($_POST["firstName"]) && isset($_POST["lastName"]) && isset($_POST["email"])) {
            $first_name = sanitize_text_field($_POST["firstName"]);
            $last_name = sanitize_text_field($_POST["lastName"]);
            $email = sanitize_email($_POST["email"]);
            $urlSite = home_url();
            $urlApi = WRPW_URLAPP . "/v1/ecommerce/autoRegister";
            $post_fields = array();
            $post_fields["name"] = $first_name;
            $post_fields["surname"] = $last_name;
            $post_fields["email"] = $email;
            $post_fields["host"] = $urlSite;
            $response = wp_remote_post($urlApi, array(
                    'method' => 'POST',
                    'body' => $post_fields
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo wiremo_wh_log("----------Request url route: /v1/ecommerce/autoRegister return error " . esc_attr($error_message) . " error ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
            } else {
                $data_request = json_decode($response["body"]);
                $response_message = array();
                $response_code = $response["response"]["code"];
                if ($response_code == 400) {
                    $response_message["message"] = $data_request->error;
                    echo json_encode($response_message);
                    die();
                }
                if ($data_request->success == true) {
                    $siteId = $data_request->data->siteId;
                    $apiKey = $data_request->data->apiKey;
                    $response_message["success"] = true;
                    $response_message["message"] = "success";
                    $response_message["apiKey"] = $data_request->data->apiKey;
                    (get_option("wiremo-siteId")) ? update_option("wiremo-siteId", $siteId) : add_option("wiremo-siteId", $siteId);
                    (get_option("wiremo-api-key")) ? update_option("wiremo-api-key", $apiKey) : add_option("wiremo-api-key", $apiKey);
                } else {
                    $response_message["success"] = false;
                    if ($data_request->data->msg == 1) {
                        $response_message["message"] = $error_empty_host;
                    } else if ($data_request->data->msg == 2) {
                        $response_message["message"] = $error_user_exists;
                    }
                }
                echo json_encode($response_message);
            }
        }
        die();
    }

    function wiremoGetSiteId()
    {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoGetSiteId')){
            wp_die('Request not trusted');
        }
        $error_connect_message = 'We were able to connect your Wiremo account but <strong>'.home_url().'</strong> isn’t configured under your account.<br>
        Do you want us to update your domain to <strong>'.home_url().'</strong> ?<br>
        <a class="wrmr-connect-yes" href="#">Yes</a> / <a class="wrmr-connect-no" href="#">No</a>
        ';
        $urlApi = WRPW_URLAPP . "/v1/ecommerce/checkSite";
        $urlSite = home_url();
        global $siteId, $apiKey, $registerHooks;
        $siteId = esc_attr(get_option("wiremo-siteId"));
        $apiKey = sanitize_text_field($_POST["apiKey"]);
        $registerHooks = esc_attr(get_option("wiremo-register-hooks"));
        if (isset($siteId) && !empty($siteId) && isset($apiKey) && !empty($apiKey) && isset($registerHooks) && !empty($registerHooks)) {
            include 'user-settings.php';
        } else {
            $error_message = array();
            $response_data = json_decode(wiremo_checksite($urlSite, $urlApi, $apiKey));
            $data = json_decode($response_data->body);
            $code_error = $response_data->response;
            if (isset($data->siteId) && !empty($data->siteId) && $code_error->code == 200) {
                (get_option("wiremo-siteId")) ? update_option("wiremo-siteId", $data->siteId) : add_option("wiremo-siteId", $data->siteId);
                $error_message["success"] = true;
            } else if ($code_error->code == 404) {
                $error_message["success"] = false;
                $error_message["message"] = $error_connect_message;
            } else {
                $error_message["success"] = false;
                $error_message["message"] = $error_connect_message;
            }
            echo json_encode($error_message);
        }
        die();
    }

    function wiremoValidateSite() {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoValidateSite')){
            wp_die('Request not trusted');
        }
        $error_validate = "Something went wrong please try again or <a target='_blank' href='https://wiremo.co/contact-us/'>contact our support team</a>";
        $urlApi = WRPW_URLAPP."/v1/ecommerce/validateSite";
        $apikey = sanitize_text_field($_POST["apiKey"]);
        $url = sanitize_text_field($_POST["url"]);
        if(!empty($apikey) && !empty($url)) {
            $post_fields = array();
            $post_fields["url"] = home_url();
            $post_fields["apiKey"] = $apikey;
            $error_data = array();
            $response = wp_remote_post( $urlApi, array(
                    'method' => 'POST',
                    'body' => $post_fields
                )
            );

            if ( is_wp_error( $response ) ) {
                $error_message = $response->get_error_message();
                $error_data["success"] = false;
                $error_data["message"] = $error_message;
            } else {
                $data_response =  $response;
                $data_body = json_decode($response["body"]);
                $code_error = $data_response["response"];
                if($code_error["code"] == 200) {
                    (get_option("wiremo-siteId")) ? update_option("wiremo-siteId", $data_body->siteId) : add_option("wiremo-siteId", $data_body->siteId);
                    $error_data["success"] = true;
                    $error_data["message"] = "success";
                }
                else {
                    $error_data["success"] = false;
                    $error_data["message"] = $error_validate;
                }
            }
            echo json_encode($error_data);
        }
        die();
    }

    function wiremoNoValidate() {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoNoValidate')){
            wp_die('Request not trusted');
        }
        $error_message = 'OK, no problems!<br>
        Go to your Wiremo Dashboard -> Install Wiremo and enter <strong>'.home_url().'</strong> in Step 1 then click “Next” button and follow the wizard.<br>
        Then come back to your WordPress dashboard and click on “Connect your Wiremo account” and the plugin will do the magic!<br>
        If you have any issue just <a target="_blank" href=\'https://wiremo.co/contact-us/\'>contact our support team</a>';
        echo $error_message;
        die();
    }

    function wiremoAddApiKey()
    {


        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoAddApiKey')){
            wp_die('Request not trusted');
        }
        if (isset($_POST["apiKey"]) && !empty($_POST["apiKey"])) {
            $apiKey = sanitize_text_field($_POST["apiKey"]);
            echo $apiKey;
            (get_option("wiremo-api-key")) ? update_option("wiremo-api-key", $apiKey) : add_option("wiremo-api-key", $apiKey);
            echo wiremo_wh_log("----------Log: Received api key ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
        }
        die();
    }

    function wiremoAddRegisterHook()
    {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremoAddRegisterHook')){
            wp_die('Request not trusted');
        }
        if (isset($_POST["apiKey"]) && !empty($_POST["apiKey"])) {
            $protocol = $_SERVER['HTTPS'] ? "https" : "http";
            $urlSite = $protocol."://".$_SERVER['HTTP_HOST'];
            $apiKey = sanitize_text_field($_POST["apiKey"]);
            $siteId = esc_attr(get_option("wiremo-siteId"));
            $urlApi = WRPW_URLAPP . "/v1/ecommerce/" . $siteId . "/register";
            $post_fields = array();
            $post_fields["apiKey"] = $apiKey;
            $post_fields["type"] = "woocommerce";
            $post_fields["url"] = $urlSite;
            $post_fields["restUrl"] = get_rest_url();
            $response = wp_remote_post($urlApi, array(
                    'method' => 'POST',
                    'body' => $post_fields
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo esc_attr($error_message);
            } else {
                $data = json_decode($response["body"]);
                if (!empty($data)) {
                    (get_option("wiremo-register-hooks")) ? update_option("wiremo-register-hooks", true) : add_option("wiremo-register-hooks", true);
                    echo wiremo_wh_log("----------Log: Registered hooks ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
                }
                echo $response["body"];
            }
        }
        die();
    }


    function importReviewsToWiremo()
    {
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'importReviewsToWiremo')){
            wp_die('Request not trusted');
        }
        $args = array(
            'status' => 'approve',
            'post_status' => 'publish',
            'post_type' => 'product',
            'order' => 'ASC'
        );

        $comments = get_comments($args);
        $reviews = array();
        $comments_ids = array();
        $limit_replies = 0;
        $reviews[] = 'title' . '~' . 'comment_ID' . '~' . 'parent' . '~' . 'path' . '~' . 'name' . '~' . 'avatarUrl' . '~' . 'message' . '~' . 'rating' . '~' . 'dateTime' . '~' . 'identifier';
        for ($j = 0; $j < count($comments); $j++) {
            if ($comments[$j]->comment_parent == 0) {
                $comments_ids[] = $comments[$j]->comment_ID;
            }
        }
        for ($i = 0; $i < count($comments); $i++) {
            $comment_id = $comments[$i]->comment_ID;
            $rating = get_comment_meta($comments[$i]->comment_ID, 'rating', true);
            $avatarUrl = get_avatar_url($comments[$i]->comment_author_email);
            $postId = $comments[$i]->comment_post_ID;
            $urlproduct = get_permalink($postId);
            $hostname = get_home_url();
            $pathname = str_replace($hostname, "", $urlproduct);
            $title = get_the_title($postId);
            $message = $comments[$i]->comment_content;
            if($message[0] == '"') {
                $message = substr( $message, 1 );
            }
            $message = str_replace(array("\r", "\n"), '', $message);
            $parent = '';
            if ($comments[$i]->comment_parent == 0) {
                $reviews[] = $title . '~' . $comment_id . '~' . $parent . '~' . $pathname . '~' . $comments[$i]->comment_author . '~' . $avatarUrl . '~' . $message . '~' . $rating . '~' . $comments[$i]->comment_date . '~' . $postId;
            } else {
                if ($comments[$i]->user_id > 0) {
                    $user_info = get_userdata($comments[$i]->user_id);
                    if (count($user_info->roles) > 0) {
                        if (in_array("administrator", $user_info->roles) && in_array($comments[$i]->comment_parent, $comments_ids)) {
                            if ($limit_replies < 1) {
                                $rating = '';
                                $parent = $comments[$i]->comment_parent;
                                $reviews[] = $title . '~' . $comment_id . '~' . $parent . '~' . $pathname . '~' . $comments[$i]->comment_author . '~' . $avatarUrl . '~' . $message . '~' . $rating . '~' . $comments[$i]->comment_date . '~' . $postId;
                            }
                            $limit_replies = $limit_replies + 1;
                        }
                    }
                }
            }
        }
        $headers = "{\"comment_ID\":[1],\"title\":[0],\"path\":[3],\"name\":[4],\"message\":[6],\"rating\":[7],\"dateTime\":[8],\"avatarUrl\":[5],\"identifier\" : [9],\"parent\" : [2]}";

        ob_start();
        $outstream = fopen("php://output", 'w');
        foreach ($reviews as $review) {
            fputcsv($outstream, array($review), '~', ' ');
        }
        fclose($outstream) or die();
        $csv_data = ob_get_clean();
        $url = WRPW_URLAPP . '/v1/reviews/fake';
        $apiKey = get_option("wiremo-api-key");
        $delimiter = '-------------' . uniqid();
        $fileFields = array(
            "file" => array(
                "type" => "text/csv",
                "content" => $csv_data,
            ),
        );
        $postFields = array(
            "headers" => $headers,
            "hasHeader" => "true",
            "apiKey" => $apiKey,
            "delimiter" => "~"
        );

        $data = "";
        foreach ($fileFields as $name => $file) {
            $data .= "--" . $delimiter . "\r\n";
            $data .= 'Content-Disposition: form-data; name="' . $name . '";' .
                ' filename="wiremo-export.csv"' . "\r\n";
            $data .= 'Content-Type: ' . $file['type'] . "\r\n";
            $data .= "\r\n";
            $data .= $file['content'] . "\r\n";
        }

        foreach ($postFields as $name => $content) {
            $data .= "--" . $delimiter . "\r\n";
            $data .= 'Content-Disposition: form-data; name="' . $name . '"';
            $data .= "\r\n\r\n";
            $data .= $content;
            $data .= "\r\n";
        }
        $data .= "--" . $delimiter . "--\r\n";
        $objMessage = new StdClass();
        if (count($comments) == 0) {
            $response = __("You don't have any reviews", "wiremo-widget");
            $status = false;
        } else {
            $args = array(
                'method' => 'POST',
                'body' => $data,
                'timeout' => '20',
                'headers' => array(
                    'Content-Type' => 'multipart/form-data; boundary=' . $delimiter,
                    'Content-Length' => strlen($data)
                ),
            );

            $post_result = wp_remote_post( $url, $args );

            if ( is_wp_error($post_result) ) {
                $err = $post_result->get_error_message();
                $response = __("wpPost Error #:", "wiremo-widget") . $err;
                $status = false;
            } else {
                $result = $post_result['body'];
                ($result === "\"job started\"") ? add_option("wiremo-display-import", true) : "";
                $response = ($result === "\"job started\"") ? __("Your reviews successful imported in wiremo.", "wiremo-widget") : __("You have an error", "wiremo-widget");
                $status = ($result === "\"job started\"") ? true : false;
                if ($result === "\"job started\"") {
                    echo wiremo_wh_log("----------Log: Success  imported " . count($comments) . "reviews from woocommerce to wiremo ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
                } else {
                    echo wiremo_wh_log("----------Log: Result = " . $result . " ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
                }
            }
        }

        $objMessage->message = $response;
        $objMessage->state = $status;
        $objMessage = json_encode($objMessage);
        echo $objMessage;
        die();
    }

    function importWiremoStatistics()
    {



        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'importWiremoStatistics')){
            wp_die('Request not trusted');
        }
        if (isset($_POST["id"]) && !empty($_POST["id"])) {

            $wr_apikey = get_option("wiremo-api-key");
            $wr_id = sanitize_text_field($_POST["id"]);
            $wr_identifiers = sanitize_text_or_array_field($_POST["identifiers"]);
            $wr_id = (int)$wr_id;

            $wr_identifiers_arr = array();
            for ($i = 0; $i < count($wr_identifiers); $i++) {
                $wr_identifiers_arr[] = $wr_identifiers[$i]["identifier"];



          }	
            if (count($wr_identifiers_arr) > 0) {
                $wr_url_api = WRPW_URLAPP . "/v1/ecommerce/statistics/sync";
                $post_fields = array(
                    "apiKey" => $wr_apikey,
                    "identifiers" => $wr_identifiers_arr
                );
                $response = wp_remote_post($wr_url_api, array(
                        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
                        'method' => 'POST',
                        'body' => json_encode($post_fields)
                    )
                );

                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    echo esc_attr($error_message);

                } else {
                    $data = json_decode($response["body"],true);


                  for ($i = 0; $i < count($data); $i++) {
                        $stats = $data[$i]['stats'];
			$ident = $data[$i]['identifier'];
                        if (is_numeric($data[$i]['identifier'])) {
                        $identifier = $data[$i]['identifier'];
                      } else {
                       $identifier = url_to_postid(home_url() . "/" . $data[$i]->identifier);
                        }
                        $countReview = $stats['count'];
                        $ratingSum = $stats['ratingSum'];
                        $rating_sum_1 = $stats['detailedData'][0]['ratingSum'];
                        $rating_sum_2 = $stats['detailedData'][1]['ratingSum'];
                        $rating_sum_3 = $stats['detailedData'][2]['ratingSum'];
                        $rating_sum_4 = $stats['detailedData'][3]['ratingSum'];
                        $rating_sum_5 = $stats['detailedData'][4]['ratingSum'];

                        $average_rating = ($ratingSum != 0) ? ($rating_sum_1 + $rating_sum_2 + $rating_sum_3 + $rating_sum_4 + $rating_sum_5) / $countReview : 0;
                        if ((int)$average_rating != $average_rating) {
                            $average_rating = number_format($average_rating, 2);
                        }
                        if ($identifier != 0) {
                            $post_type = get_post_type($identifier);
                            if ($post_type == "product") {
                                if (!add_post_meta($identifier, 'wiremo-review-total', json_encode($stats), true)) {
                                    update_post_meta($identifier, 'wiremo-review-total', json_encode($stats));
                                }
                                if (!add_post_meta($identifier, 'wiremo-review-average', $average_rating, true)) {
                                    update_post_meta($identifier, 'wiremo-review-average', $average_rating);
                                }
                                if (!add_post_meta($identifier, 'wiremo-review-count', $countReview, true)) {
                                    update_post_meta($identifier, 'wiremo-review-count', $countReview);
                                }
                            }
                        }
                    }
                }
            }
        }
        $result = new stdClass();
        $result->success = "ok";
        echo json_encode($result);
        die();
    }

    function get_wiremo_email_templates($api_key)
    {
        $url_api = WRPW_URLAPP . "/v1/ecommerce/arrCampaigns/";
        $post_fields = array();
        $arr_options = array();
        $post_fields["apiKey"] = $api_key;
        $response = wp_remote_post($url_api, array(
                'method' => 'POST',
                'body' => $post_fields
            )
        );
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            wiremo_wh_log("----------Request url route: ".$url_api." return error " . esc_attr($error_message) . " error ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
        } else {
            $templates = json_decode($response["body"]);
            if(isset($templates->data)) {
                if (count($templates->data) == 0) {

                } else {
                    for ($i = 0; $i < count($templates->data); $i++) {
                        $id_template = $templates->data[$i]->_id;
                        $name_template = $templates->data[$i]->name;
                        $arr_options[$id_template] = $name_template;
                    }
                }
            }
        }
        return $arr_options;
    }

    function wiremo_check_template($template_id, $templates)
    {
        $active_template = false;
        foreach ($templates as $key => $template) {
            if ($key == $template_id) {
                $active_template = true;
            }
        }
        return $active_template;
    }

    if (!function_exists("wiremo_send_completed_orders")) {
        function wiremo_send_completed_orders()
        {
            if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremo_send_completed_orders')){
                wp_die('Request not trusted');
            }
            if (isset($_POST["page"]) && !empty($_POST["page"])) {
                $page = sanitize_text_field($_POST["page"]);
            }
            if (isset($_POST["start_date"]) && !empty($_POST["start_date"])) {
                $start_date = sanitize_text_field($_POST["start_date"]);
            }
            if (isset($_POST["end_date"]) && !empty($_POST["end_date"])) {
                $end_date = sanitize_text_field($_POST["end_date"]);
            }
            if (isset($_POST["emails_per_day"]) && !empty($_POST["emails_per_day"])) {
                $emails_per_day = sanitize_text_field($_POST["emails_per_day"]);
            }
            if (isset($_POST["template_id"]) && !empty($_POST["template_id"])) {
                $template_id = sanitize_text_field($_POST["template_id"]);
            }
            if (isset($_POST["template_name"]) && !empty($_POST["template_name"])) {
                $template_name = sanitize_text_field($_POST["template_name"]);
            }

            $wr_query = strtotime($start_date) . '...' . (strtotime($end_date) + 86399);

            $customer_orders_all = wc_get_orders($args = array(
                'limit' => WRPW_ORDER_LIMIT + 1,
                'date_completed' => $wr_query,
                'post_status' => 'wc-completed',
            ));

            if (!empty($start_date) && !empty($end_date) && !empty($emails_per_day) && !empty($template_id) && !empty($template_name)) {
                $api_key = get_option("wiremo-api-key");
                $campaign_id = $template_id;
                $shop_page_id = get_option('woocommerce_shop_page_id');
                $shop_page_name = get_the_title($shop_page_id);
                $orders = array();
                $count_customers = 0;
                $wr_success = true;
                $wr_page = intval(count($customer_orders_all) / WRPW_ORDER_PER_PAGE) + 1;
                if (count($customer_orders_all) <= WRPW_ORDER_LIMIT) {
                    $customer_orders = wc_get_orders($args = array(
                        'limit' => WRPW_ORDER_PER_PAGE,
                        'date_completed' => $wr_query,
                        'post_status' => 'wc-completed',
                        'paged' => $page
                    ));
                    if (count($customer_orders) >= 1) {
                        for ($i = 0; $i < count($customer_orders); $i++) {
                            $order_id = $customer_orders[$i]->get_id();
                            $order = wc_get_order($order_id);
                            $order_meta = get_post_meta($order_id);
                            $items = $order->get_items();
                            $date = get_post_meta($order_id, "_completed_date", true);
                            $date = new DateTime($date);
                            $order_date = $date->format('Y-m-d\TH:i:s.u');
                            $order_date_split = explode(".", $order_date);
                            $order_date = $order_date_split[0] . ".000Z";
                            $order_format = explode("T", $order_date);
                            $order_format = $order_format[0];
                            if ($order_format >= $start_date && $order_format <= $end_date) {
                                $item = array();
                            }
                            $first_name = $order_meta["_billing_first_name"][0];
                            $last_name = $order_meta["_billing_last_name"][0];
                            $email = $order_meta["_billing_email"][0];
                            if (wiremo_validate_gravatar($email)) {
                                $avatar_url = get_avatar_url($email);
                            } else {
                                $avatar_url = '';
                            }
                            foreach ($items as $item_id => $item_data) {
                                $product_name = $item_data['name'];
                                $product_id = $item_data['product_id'];
                                $product_description = get_post($product_id)->post_excerpt;
                                $productId = $item_data['product_id'];
                                if (class_exists('SitePress')) {
                                    $wrpw_wpml_data = new WiremoWpmlData();
                                    $productId= $wrpw_wpml_data->wrpw_get_main_product_id($item_data['product_id']);
                                } else {
                                    $productId = $item_data['product_id'];
                                }
                                $product_url = get_the_permalink($productId);
                                $product_path = str_replace(home_url(), "", $product_url);
                                $product_path = rawurlencode($product_path);
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                                $image_product = $image[0];
                                if (isset($image_product) && !empty($image_product)) {
                                    $image_product = $image[0];
                                } else {
                                    $image_product = "";
                                }
                                if ($order_format >= $start_date && $order_format <= $end_date) {
                                    $item[] = array(
                                        "name" => $product_name,
                                        "description" => $product_description,
                                        "path" => $product_path,
                                        "url" => $product_url,
                                        "identifier" => (string)$productId,
                                        "orderDate" => $order_date,
                                        "orderId" => (string)$order_id,
                                        "imageUrl" => $image_product
                                    );
                                    $count_customers = $count_customers + 1;
                                }
                            }
                            if ($order_format >= $start_date && $order_format <= $end_date) {
                                $orders[] = array(
                                    "email" => $email,
                                    "name" => $first_name,
                                    "surname" => $last_name,
                                    "avatarUrl" => $avatar_url,
                                    "items" => $item
                                );
                            }
                        }
                        for ($j = 0; $j < count($orders); $j++) {
                            $key_orders = array_keys($orders[$j], "");
                            foreach ($key_orders as $key) {
                                unset($orders[$j][$key]);
                            }
                            for ($i = 0; $i < count($orders[$j]["items"]); $i++) {
                                $keys = array_keys($orders[$j]["items"][$i], "");
                                foreach ($keys as $key) {
                                    unset($orders[$j]["items"][$i][$key]);
                                }
                            }
                        }
                        $post_fields = array(
                            "apiKey" => $api_key,
                            "campaignId" => $campaign_id,
                            "shopName" => $shop_page_name,
                            "emailsPerDay" => $emails_per_day,
                            "orders" => $orders
                        );
                        $wr_success = true;
                    } else {
                        $wr_success = false;
                    }
                } else {
                    $wr_success = false;
                    $count_customers = count($customer_orders_all);
                }
            }

            if ($wr_success == true) {
                $url_api = WRPW_URLAPP . "/v1/ecommerce/postReviewRequest";
                $response = wp_remote_post($url_api, array(
                        'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
                        'method' => 'POST',
                        'body' => json_encode($post_fields)
                    )
                );
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    echo wiremo_wh_log("----------Request url route: ".$url_api." return error " . esc_attr($error_message) . " error ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
                } else {
                    $data = json_decode($response["body"]);
                    if (isset($data->success) && !empty($data->success) && $data->success > 0) {
                        // to do if success
                        $wr_success = true;
                    } else {
                        $wr_success = false;
                        echo wiremo_wh_log("----------Request url route: ".$url_api."return error " . $data->error . " error ---------- date = " . date("d-M-Y") . " time = " . date("H:i:s"));
                    }
                }
            }
            $response = array(
                "success" => $wr_success,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "customers" => $count_customers,
                "template_name" => $template_name,
                "limit" => $wr_page
            );
            echo json_encode($response);
            die();
        }
    }

    if (!function_exists("wiremo_save_campaign_information")) {
        function wiremo_save_campaign_information()
        {
            if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wiremo_save_campaign_information')){
                wp_die('Request not trusted');
            }
            if (isset($_POST["start_date"]) && !empty($_POST["start_date"])) {
                $start_date = sanitize_text_field($_POST["start_date"]);
            }
            if (isset($_POST["end_date"]) && !empty($_POST["end_date"])) {
                $end_date = sanitize_text_field($_POST["end_date"]);
            }
            if (isset($_POST["template_name"]) && !empty($_POST["template_name"])) {
                $template_name = sanitize_text_field($_POST["template_name"]);
            }
            if (isset($_POST["customers"]) && !empty($_POST["customers"])) {
                $customers = sanitize_text_or_array_field($_POST["customers"]);
            }
            $campaigns_information = array(
                "start_date" => $start_date,
                "end_date" => $end_date,
                "customers" => $customers,
                "template_name" => $template_name
            );
            if (get_option("wiremo_total_campaigns")) {
                $wiremo_total_campaigns = json_decode(get_option("wiremo_total_campaigns"));
                if ($wiremo_total_campaigns->count > 0) {
                    $campaigns_count = $wiremo_total_campaigns->count + 1;
                    $campaigns_total = array(
                        "count" => $campaigns_count
                    );
                    if (!update_option("wiremo_total_campaigns", json_encode($campaigns_total))) {
                        add_option("wiremo_total_campaigns", json_encode($campaigns_total));
                    }
                    if (!update_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information))) {
                        add_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information));
                    }
                } else {
                    $campaigns_total = array(
                        "count" => 1
                    );
                    if (!update_option("wiremo_total_campaigns", json_encode($campaigns_total))) {
                        add_option("wiremo_total_campaigns", json_encode($campaigns_total));
                    }
                    if (!update_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information))) {
                        add_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information));
                    }
                }
            } else {
                $campaigns_total = array(
                    "count" => 1
                );
                if (!update_option("wiremo_total_campaigns", json_encode($campaigns_total))) {
                    add_option("wiremo_total_campaigns", json_encode($campaigns_total));
                }
                if (!update_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information))) {
                    add_option("wiremo_campaigns_" . $campaigns_total["count"], json_encode($campaigns_information));
                }
            }
            $response = array(
                "success" => true,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "customers" => $customers,
                "template_name" => $template_name
            );
            echo json_encode($response);
            die();
        }
    }

    if (!function_exists("wiremo_get_orders_datetime")) {
        function wiremo_get_orders_datetime()
        {
            $wiremo_date_time = new StdClass();
            $customer_orders_old = wc_get_orders($args = array(
                'limit' => 1,
                'orderby' => '_date_completed',
                'order' => 'ASC',
                'date_completed' => '>0',
                'post_status' => 'wc-completed',
            ));
            $old_date = "";
            $future_date = "";
            if (count($customer_orders_old) > 0) {
                for ($i = 0; $i < count($customer_orders_old); $i++) {
                    $order_id = $customer_orders_old[$i]->get_id();
                    $date = get_post_meta($order_id, "_completed_date", true);
                    $date = new DateTime($date);
                    $order_date = $date->format('Y-m-d H:i:s');
                    $order_date = strtotime($order_date);
                    $old_date = date("Y-m-d", $order_date);
                }
            } else {
                $old_date = "";
            }

            $customer_orders_news = wc_get_orders($args = array(
                'limit' => 1,
                'orderby' => '_date_completed',
                'order' => 'DESC',
                'date_completed' => '>0',
                'post_status' => 'wc-completed',
            ));
            if (count($customer_orders_news) > 0) {
                for ($i = 0; $i < count($customer_orders_news); $i++) {
                    $order_id = $customer_orders_news[$i]->get_id();
                    $date = get_post_meta($order_id, "_completed_date", true);
                    $date = new DateTime($date);
                    $order_date = $date->format('Y-m-d H:i:s');
                    $order_date = strtotime($order_date);
                    $future_date = date("Y-m-d", $order_date);
                }
            } else {
                $future_date = "";
            }


            $wiremo_date_time->old_date = $old_date;
            $wiremo_date_time->future_date = $future_date;
            return $wiremo_date_time;
        }
    }

    if(!function_exists("wrpw_reset_old_identifiers")) {
        function wrpw_reset_old_identifiers() {
            if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wrpw_reset_old_identifiers')){
                wp_die('Request not trusted');
            }

            global $wpdb;
            $wrpw_ids_wordpress = array();
            $wrpw_ids_resets = array();
            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type='product'", OBJECT );
            foreach($results as $result) {
                $wrpw_ids_wordpress[] = $result->ID;
            }
            if(isset($_POST["wiremoIdentifiers"])) {
                $wiremo_identifiers = sanitize_text_or_array_field($_POST["wiremoIdentifiers"]);
                for ($i = 0; $i < count($wrpw_ids_wordpress); $i++) {
                    if (!in_array($wrpw_ids_wordpress[$i], $wiremo_identifiers)) {
                        $wrpw_ids_resets[] = $wrpw_ids_wordpress[$i];
                    }
                }
                for($j=0;$j<count($wrpw_ids_resets);$j++) {
                    $post_type = get_post_type($wrpw_ids_resets[$j]);
                    if ($post_type == "product") {
                        if (get_post_meta($wrpw_ids_resets[$j], 'wiremo-review-total')) {
                            delete_post_meta($wrpw_ids_resets[$j], 'wiremo-review-total');
                        }
                        if (get_post_meta($wrpw_ids_resets[$j], 'wiremo-review-average')) {
                            delete_post_meta($wrpw_ids_resets[$j], 'wiremo-review-average');
                        }
                        if (get_post_meta($wrpw_ids_resets[$j], 'wiremo-review-count')) {
                            delete_post_meta($wrpw_ids_resets[$j], 'wiremo-review-count');
                        }
                    }
                }
            }
            die();
        }
    }

    function wiremo_ajaxurl()
    {
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }

    add_action('wp_head', 'wiremo_ajaxurl');
    add_action('wp_ajax_wiremoAutoRegister', 'wiremoAutoRegister');
    add_action('wp_ajax_nopriv_wiremoAutoRegister', 'wiremoAutoRegister');
    add_action('wp_ajax_wiremoAuth', 'wiremoAuth');
    add_action('wp_ajax_nopriv_wiremoAuth', 'wiremoAuth');
    add_action('wp_ajax_wiremoAuth', 'wiremoAuth');
    add_action('wp_ajax_nopriv_wiremoValidateSite', 'wiremoValidateSite');
    add_action('wp_ajax_wiremoValidateSite', 'wiremoValidateSite');
    add_action('wp_ajax_nopriv_wiremoNoValidate', 'wiremoNoValidate');
    add_action('wp_ajax_wiremoNoValidate', 'wiremoNoValidate');
    add_action('wp_ajax_wiremoGetSiteId', 'wiremoGetSiteId');
    add_action('wp_ajax_nopriv_wiremoGetSiteId', 'wiremoGetSiteId');
    add_action('wp_ajax_wiremoAddApiKey', 'wiremoAddApiKey');
    add_action('wp_ajax_nopriv_wiremoAddApiKey', 'wiremoAddApiKey');
    add_action('wp_ajax_wiremoAddRegisterHook', 'wiremoAddRegisterHook');
    add_action('wp_ajax_nopriv_wiremoAddRegisterHook', 'wiremoAddRegisterHook');
    add_action('wp_ajax_importReviewsToWiremo', 'importReviewsToWiremo');
    add_action('wp_ajax_nopriv_importReviewsToWiremo', 'importReviewsToWiremo');
    add_action('wp_ajax_importWiremoStatistics', 'importWiremoStatistics');
    add_action('wp_ajax_nopriv_importWiremoStatistics', 'importWiremoStatistics');
    add_action('wp_ajax_wiremo_send_completed_orders', 'wiremo_send_completed_orders');
    add_action('wp_ajax_nopriv_wiremo_send_completed_orders', 'wiremo_send_completed_orders');
    add_action('wp_ajax_wiremo_save_campaign_information', 'wiremo_save_campaign_information');
    add_action('wp_ajax_nopriv_wiremo_save_campaign_information', 'wiremo_save_campaign_information');
    add_action('wp_ajax_wrpw_reset_old_identifiers', 'wrpw_reset_old_identifiers');
    add_action('wp_ajax_nopriv_wrpw_reset_old_identifiers', 'wrpw_reset_old_identifiers');
}
