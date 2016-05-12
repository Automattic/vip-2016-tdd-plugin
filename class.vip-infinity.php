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
		if ( empty( $more_recent_post ) ) {
			return new WP_Error( 'recent_post_not_found', 'No recent post found', array( 'status' => 404 ) );
		}
		$posts = get_posts( 
			array( 
				'post_status' => 'publish', 
				'post_type' => 'post', 
				'date_query' => array( 'before' => $more_recent_post->post_date ),
				'suppress_filters' => false
			)
		);

		if ( count( $posts ) === 0 ) {
			return new WP_Error( 'next_post_not_found', 'No next post found', array( 'status' => 404 ) );
		}

		$next_post = $posts[0];

		return array(
			'ID' => $next_post->ID,
			'post_title' => $next_post->post_title,
			'post_content' => $next_post->post_content,
			'post_date' => $next_post->post_date,
		);
	}
}