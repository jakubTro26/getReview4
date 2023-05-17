<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wiremo_get_widget_settings($url)
{
    $response = wp_remote_get( $url);
    if( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return $error_message;
    }
    else {
        $data = json_decode($response["body"]);
        if(!empty($data)) {
            echo wiremo_wh_log("----------Log: Success received widget settings (star color, star style, star size, language) ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
        }
        else {
            echo wiremo_wh_log("----------Log: Result  = ".$response["body"]." ---------- date = ".date("d-M-Y")." time = ".date("H:i:s"));
        }
        return $data;
    }
}