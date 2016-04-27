<?php

/**
 * Bootstrap the plugin unit testing environment.
 *
 * Edit 'active_plugins' setting below to point to your main plugin file.
 *
 * @package wordpress-plugin-tests
 */

// Support for:
// 1. `WP_DEVELOP_DIR` environment variable
// 2. Plugin installed inside of WordPress.org developer checkout
// 3. Tests checked out to /tmp
if( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	$test_root = getenv( 'WP_DEVELOP_DIR' );
} else if ( file_exists( '../../../../tests/phpunit/includes/bootstrap.php' ) ) {
	$test_root = '../../../../tests/phpunit';
} else if ( file_exists( '/tmp/wordpress-tests-lib/includes/bootstrap.php' ) ) {
	$test_root = '/tmp/wordpress-tests-lib';
}

require $test_root . '/includes/functions.php';

$wp_api_plugin_path = dirname( __FILE__ ) . '/../../../WP-API';

// Activates this plugin and its dependency in WordPress so it can be tested.
function _manually_load_plugin() {
	require dirname( __FILE__ ) . '/../../../WP-API/plugin.php';
	require dirname( __FILE__ ) . '/../../infinity.php';
}
tests_add_filter( 'plugins_loaded', '_manually_load_plugin' );

// this is copied/modified from the WP-API plugin v2
define( 'REST_TESTS_IMPOSSIBLY_HIGH_NUMBER', 99999999 );
define( 'REST_REQUEST', true );

require $test_root . '/includes/bootstrap.php';

// Helper classes
if ( ! class_exists( 'WP_Test_REST_TestCase' ) ) {
	require_once $wp_api_plugin_path . '/tests/class-wp-test-rest-testcase.php';
}
function test_rest_expand_compact_links( $links ) {
	if ( empty( $links['curies'] ) ) {
		return $links;
	}
	foreach ( $links as $rel => $links_array ) {
		if ( ! strpos( $rel, ':' ) ) {
			continue;
		}

		$name = explode( ':', $rel );

		$curie = wp_list_filter( $links['curies'], array( 'name' => $name[0] ) );
		$full_uri = str_replace( '{rel}', $name[1], $curie[0]['href'] );
		$links[ $full_uri ] = $links_array;
		unset( $links[ $rel ] );
	}
	return $links;
}

require_once $wp_api_plugin_path . '/tests/class-wp-test-rest-controller-testcase.php';
require_once $wp_api_plugin_path . '/tests/class-wp-test-rest-post-type-controller-testcase.php';
require_once $wp_api_plugin_path . '/tests/class-wp-test-spy-rest-server.php';
require_once $wp_api_plugin_path . '/tests/class-wp-rest-test-controller.php';

