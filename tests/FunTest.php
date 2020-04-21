<?php
namespace Fun;

use WP_Error;
use WP_Http;
use WP_REST_Request;
use WP_REST_Response;

class FunTest extends \PHPUnit\Framework\TestCase {

	function testBuildHandlerGetRequestorFailure(): void {
		$handler = build_handler(
			fn() => new WP_Error(
				'fun',
				'No requestor',
				[ 'status' => WP_Http::UNAUTHORIZED ]
			),
			__NAMESPACE__ . '\not_implemented',
			__NAMESPACE__ . '\not_implemented',
			__NAMESPACE__ . '\not_implemented',
		);
		$response = $handler( new WP_REST_Request() );

		$this->assertEquals( WP_Http::UNAUTHORIZED, $response->get_status(),  );
		$this->assertEquals(
			[
				'code' => 'fun',
				'message' => 'No requestor',
			],
			$response->get_data()
		);
	}

	function testBuildHandlerResourceFailure(): void {
		$handler = build_handler(
			fn(): FunTest\User => new FunTest\User,
			fn() => new WP_Error( 'fun', 'No resource', [ 'status' => WP_Http::NOT_FOUND ] ),
			__NAMESPACE__ . '\not_implemented',
			__NAMESPACE__ . '\not_implemented',
		);

		$response = $handler( new WP_REST_Request() );
		$this->assertEquals( 404, $response->get_status() );
		$this->assertEquals( [ 'code' => 'fun', 'message' => 'No resource' ], $response->get_data() );
	}

	function testBuildHandlerAuthorizationFailure(): void {
		$handler = build_handler(
			fn(): FunTest\User => new FunTest\User,
			fn(): FunTest\Resource => new FunTest\Resource(),
			fn() => new WP_Error( 'fun', 'Not authorized', [ 'status' => WP_Http::UNAUTHORIZED ] ),
			__NAMESPACE__ . '\not_implemented',
		);

		$response = $handler( new WP_REST_Request() );
		$this->assertEquals( WP_Http::UNAUTHORIZED, $response->get_status() );
		$this->assertEquals( [ 'code' => 'fun', 'message' => 'Not authorized'], $response->get_data() );
	}

	function testBuildHandlerActionFailure(): void {
		$handler = build_handler(
			fn(): FunTest\User => new FunTest\User,
			fn(): FunTest\Resource => new FunTest\Resource(),
			fn() => true,
			fn() => new WP_Error( 'fun', 'Bad request', [ 'status' => WP_Http::BAD_REQUEST ] )
		);

		$response = $handler( new WP_REST_Request() );
		$this->assertEquals( WP_Http::BAD_REQUEST, $response->get_status() );
		$this->assertEquals( [ 'code' => 'fun', 'message' => 'Bad request'], $response->get_data() );
	}

	function testBuildHandlerSuccess(): void {
		$handler = build_handler(
			fn(): FunTest\User => new FunTest\User,
			fn(): FunTest\Resource => new FunTest\Resource(),
			/**
			 * @param WP_REST_Request $request
			 * @param FunTest\Resource $resource
			 * @param FunTest\User $user
			 */
			fn( $request, $resource, $user ) => true,
			'\Fun\FunTest\some_action'
		);

		$response = $handler( new WP_REST_Request() );

		$this->assertEquals( WP_Http::OK, $response->get_status() );
		$this->assertEquals( [ 'status' => 'ok' ], $response->get_data() );

	}
}

function not_implemented(): WP_Error {
	return new WP_Error(
		'fun',
		'Not implemented',
		[ 'status' => WP_Http::NOT_IMPLEMENTED ]
	);
}

namespace Fun\FunTest;

class User {

}

class Bot {

}

class Resource {

}

class OtherResource {

}

/**
 * @param \WP_REST_Request $request
 * @param OtherResource $resource
 * @param User $user
 * @return \WP_REST_Response
 */
function some_action( $request, $resource, $user ) {
	return new \WP_REST_Response(  [ 'status' => 'ok' ] );
}
