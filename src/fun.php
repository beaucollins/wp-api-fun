<?php
/**
 * Plugin Name: Fun
 */
namespace Fun;

use WP_REST_Response;
use WP_REST_Request;
use WP_Error;

/**
 * @template Requestor
 * @param (callable(WP_REST_Request): (Requestor|WP_Error)) $get_requestor
 * @return callable(WP_REST_Request): WP_REST_Response
 */
function build_handler( $get_requestor ) {
	return function( WP_REST_Request $request) use ( $get_requestor ): WP_REST_Response {
		$requestor = $get_requestor( $request );
		if ( $requestor instanceof WP_Error ) {
			return wp_error_as_response( $requestor );
		}
		$resource = resource_for_request( $request, $requestor );
		if ( ! requestor_can_perform( $request, $resource, $requestor ) ) {
		   return new WP_REST_Response( ['error' => '$reason'], 403  );
		}
		$representation = perform_request_action( $request, $resource, $requestor );
		return new WP_REST_Response( $representation );
	};
}

function wp_error_as_response( WP_Error $error ): WP_REST_Response {
	/**
	 * @var mixed
	 */
	$data = $error->get_error_data();
	if ( is_array( $data ) && array_key_exists( 'status', $data ) ) {
		$status = (int) $data['status'];
	} else {
		$status = 500;
	}
	return new WP_REST_Response(
		[
			'code' => $error->get_error_code(),
			'message' => $error->get_error_message()
		],
		$status
	);
}
