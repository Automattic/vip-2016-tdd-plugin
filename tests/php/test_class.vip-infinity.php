<?php

require_once dirname( __FILE__ ) . '/../../class.vip-infinity.php';

class WP_Test_VipInfinity extends WP_UnitTestCase {
	
	protected $server;

	public function setUp() {
		parent::setUp();
		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_Test_Spy_REST_Server;
		do_action( 'rest_api_init' );
	}

	public function tearDown() {
		parent::tearDown();
		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$wp_rest_server = null;
	}

	function test_endpoint_returns_404_invalid_article() {
		$request = new WP_REST_Request( 'GET', '/vip-infinity/v1/next-article/' . 999999999 );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 404, $response );
		$this->assertResponseData( array(
			'code' => 'recent_post_not_found',
		), $response );
	}

	function test_endpoint_returns_next_article() {
		$recent = new DateTime('2016-04-12');
		$a_while_ago = new DateTime('2016-03-05');
		$mysql_format = 'Y-m-d H:i:s';

		$first_article_id = $this->factory->post->create( array( 'post_status' => 'publish', 'post_date' => $recent->format($mysql_format) ) );
		$second_article_id = $this->factory->post->create( array( 'post_status' => 'publish', 'post_date' => $a_while_ago->format($mysql_format) ) );

		$this->assertNotEquals( $first_article_id, $second_article_id );

		$request = new WP_REST_Request( 'GET', '/vip-infinity/v1/next-article/'.$first_article_id );

		$response = $this->server->dispatch( $request );

		$this->assertResponseStatus( 200, $response );
		$this->assertResponseData( array(
			'ID'     => $second_article_id,
		), $response );
	}

	function test_no_next_article_returns_false() {
		$article_id = $this->factory->post->create( array( 'post_status' => 'publish' ) );
		$request = new WP_REST_Request( 'GET', '/vip-infinity/v1/next-article/'.$article_id );

		$response = $this->server->dispatch( $request );

		$this->assertResponseStatus( 404, $response );
		$this->assertResponseData( array(
			'code'    => 'next_post_not_found',
		), $response );
	}

	function test_article_is_valid() {
		$recent = new DateTime('2016-04-12');
		$a_while_ago = new DateTime('2016-03-05');
		$mysql_format = 'Y-m-d H:i:s';

		$first_article_id = $this->factory->post->create( array( 'post_status' => 'publish', 'post_date' => $recent->format($mysql_format) ) );
		$second_article_id = $this->factory->post->create( array(
			'post_status' => 'publish',
			'post_date' => $a_while_ago->format($mysql_format),
			'post_title' => 'Hello TDD',
			'post_content' => 'TDD is great',
		) );

		$request = new WP_REST_Request( 'GET', '/vip-infinity/v1/next-article/'.$first_article_id );
		$response = $this->server->dispatch( $request );
		$this->assertResponseStatus( 200, $response );
		$this->assertResponseData( array(
			'post_title'     => 'Hello TDD',
			'post_content'   => 'TDD is great',
			'post_date'      => '2016-03-05 00:00:00'
		), $response );

	}

	protected function assertResponseStatus( $status, $response ) {
		$this->assertEquals( $status, $response->get_status() );
	}

	protected function assertResponseData( $data, $response ) {
		$response_data = $response->get_data();
		$tested_data = array();
		foreach( $data as $key => $value ) {
			if ( isset( $response_data[ $key ] ) ) {
				$tested_data[ $key ] = $response_data[ $key ];
			} else {
				$tested_data[ $key ] = null;
			}
		}
		$this->assertEquals( $data, $tested_data );
	}
}
