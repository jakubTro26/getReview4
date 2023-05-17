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











