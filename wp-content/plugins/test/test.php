<?php

/*
Plugin Name: test
Plugin URI: 
Description: test rest api
Version: 2.0
Author: Refericon
Author URI: https://refericon.pl
Text Domain: test
*/

add_action( 'rest_api_init', function () {
    register_rest_route( 'test', '/author/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'my_awesome_func',
    ) );
  } );




  





function my_awesome_func( $data ) {
    $posts = get_posts( array(
      'author' => $data['id'],
    ) );
  

      echo 'route';

    if ( empty( $posts ) ) {
      return null;
    }
  
    return $posts[0]->post_title;
  }


  // function my_awesome_func( WP_REST_Request $request ) {
  //   // You can access parameters via direct array access on the object:
  //   $param = $request['some_param'];
  
  //   // Or via the helper method:
  //   $param = $request->get_param( 'some_param' );
  
  //   // You can get the combined, merged set of parameters:
  //   $parameters = $request->get_params();
  
  //   // The individual sets of parameters are also available, if needed:
  //   $parameters = $request->get_url_params();
  //   $parameters = $request->get_query_params();
  //   $parameters = $request->get_body_params();
  //   $parameters = $request->get_json_params();
  //   $parameters = $request->get_default_params();
  
  //   // Uploads aren't merged in, but can be accessed separately:
  //   $parameters = $request->get_file_params();
  // }








