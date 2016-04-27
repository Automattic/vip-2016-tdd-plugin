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

	function test_init_does_something() {
		VipInfinity::init();
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

		$this->assertEquals( 200, $response->get_status() );
		$this->assertEquals( $second_article_id, $response->get_data()['id'] );
	}
}