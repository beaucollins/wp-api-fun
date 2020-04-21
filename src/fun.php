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
 * @template Resource
 * @param (callable(WP_REST_Request): (Requestor|WP_Error)) $get_requestor
 * @param (callable(WP_REST_Request, Requestor): (Resource|WP_Error)) $get_resource
 * @param (callable(WP_REST_Request, Resource, Requestor): (true|WP_Error)) $is_authorized
 * @param (callable(WP_REST_Request, Resource, Requestor): (WP_REST_Response|WP_Error)) $action
 * @return callable(WP_REST_Request): WP_REST_Response
 */
function build_handler( $get_requestor, $get_resource, $is_authorized, $action ) {
	return function( WP_REST_Request $request) use ( $get_requestor, $get_resource, $is_authorized, $action ): WP_REST_Response {
		$requestor = $get_requestor( $request );
		if ( $requestor instanceof WP_Error ) {
			return wp_error_as_response( $requestor );
		}

		$resource = $get_resource( $request, $requestor );
		if ( $resource instanceof WP_Error ) {
			return wp_error_as_response( $resource );
		}

		$authorized = $is_authorized( $request, $resource, $requestor );
		if ( $authorized instanceof WP_Error ) {
			return wp_error_as_response( $authorized );
		}

		$representation = $action( $request, $resource, $requestor );
		if ( $representation instanceof WP_Error ) {
			return wp_error_as_response( $representation );
		}

		return $representation;
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
