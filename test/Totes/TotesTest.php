<?php
namespace Totes;

use WP_REST_Request;

class TotesTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @return void
	 */
	function testTotesNotBuggy() {
		$request = new WP_REST_Request();
		$response = totes_not_buggy( $request );

		$this->assertEquals( [ 'status' => 'not buggy' ], $response->get_data( ) );
	}
}