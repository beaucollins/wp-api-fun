<?php
/**
 * Plugin Name: Totes
 */
/**
 * Responds to a REST request with text/plain "You did it!"
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function totes_not_buggy( $request ) {
   return new WP_REST_Response( [ 'status' => 'not buggy' ] );
}

/**
 * @param string $path
 * @param (callable(WP_REST_Request):(WP_REST_Response|WP_Error|JSONSerializable)) $handler
 * @return void
 */
function totes_register_api_endpoint( $path, $handler ) {
   register_rest_route( 'totes', $path, [
      'callback' => $handler
   ] );
}

if (function_exists('add_action')) {
   add_action( 'rest_api_init', function() {
      totes_register_api_endpoint('not-buggy', 'totes_not_buggy');
   } );
}
