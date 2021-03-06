<?php

/**
 * Unit tests covering WP_JSON_Server functionality.
 *
 * @package WordPress
 * @subpackage JSON API
 */
class WP_Test_JSON_Server extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->server = new WP_JSON_Server();
	}

	public function test_envelope() {
		$data = array(
			'amount of arbitrary data' => 'alot',
		);
		$status = 987;
		$headers = array(
			'Arbitrary-Header' => 'value',
			'Multiple' => 'maybe, yes',
		);

		$response = new WP_JSON_Response( $data, $status );
		$response->header('Arbitrary-Header', 'value');

		// Check header concatenation as well
		$response->header('Multiple', 'maybe');
		$response->header('Multiple', 'yes', false);

		$envelope_response = $this->server->envelope_response( $response, false );

		// The envelope should still be a response, but with defaults
		$this->assertInstanceOf( 'WP_JSON_Response', $envelope_response );
		$this->assertEquals( 200, $envelope_response->get_status() );
		$this->assertEmpty( $envelope_response->get_headers() );
		$this->assertEmpty( $envelope_response->get_links() );

		$enveloped = $envelope_response->get_data();

		$this->assertEquals( $data,    $enveloped['body'] );
		$this->assertEquals( $status,  $enveloped['status'] );
		$this->assertEquals( $headers, $enveloped['headers'] );
	}

}
