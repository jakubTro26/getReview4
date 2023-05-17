<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wiremo_checksite($urlSite, $urlApi,$apiKey)
{
    $post_fields = array();
    $post_fields["url"] = $urlSite;
    $post_fields["apiKey"] = $apiKey;
    $response = wp_remote_post( $urlApi, array(
            'method' => 'POST',
            'body' => $post_fields
        )
    );

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        return $error_message;
    } else {
        $data = json_encode($response);
        return $data;
    }
}