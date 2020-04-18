<?php
namespace Fun;

use WP_Error;
use WP_Http;
use WP_REST_Request;

class FunTest extends \PHPUnit\Framework\TestCase {

	function testBuildHandlerGetRequestorFailure() {
		$handler = build_handler(function() {
			return new WP_Error(
				'fun',
				'No requestor',
				[ 'status' => WP_Http::UNAUTHORIZED ]
			);
		});
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
}