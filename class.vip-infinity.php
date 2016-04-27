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
		$more_recent_post = WP_Post::get_instance( $data['id'] );
		$posts = get_posts( 
			array( 
				'post_status' => 'publish', 
				'post_type' => 'post', 
				'date_query' => array( 'before' => $more_recent_post->post_date ) 
			)
		);

		if ( count( $posts ) === 0 ) {
			return false;
		}

		$next_post = $posts[0];

		return array(
			'ID' => $next_post->ID
		);
	}
}