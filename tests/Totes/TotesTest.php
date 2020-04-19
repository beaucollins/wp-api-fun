<?php
namespace Totes;

use WP_REST_Request;
use WP_REST_Server;

class TotesTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers \totes_not_buggy
	 * @return void
	 */
	function testTotesNotBuggy() {
		$request = new WP_REST_Request( 'GET', '/totes/not-buggy' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertEquals( [ 'status' => 'not buggy' ], $response->get_data( ) );
	}
}
