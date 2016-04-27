<?php

class VipInfinity {
	static function init() {
		add_action( 'rest_api_init', function () {
			register_rest_route( 'vip-infinity/v1', '/next-article/(?P<id>\d+)', array(
				'methods' => 'GET',
				'callback' => array( 'VipInfinity', 'next_article' ),
			) );
		} );
	}

	static function next_article( WP_REST_Request $data ) {
	}
}