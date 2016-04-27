<?php

/**
 * Plugin Name: Infinity
 * Plugin URI: http://infinity.com
 * Description: More plugin than any other plugin
 * Author: VIPs
 * Version: 1.0.0
 * Author URI: http://jetpack.com
 * License: GPL2+
 * Text Domain: infinity
 * Domain Path: /languages/
 */

require_once dirname( __FILE__ ) . '/class.vip-infinity.php';

add_action( 'init', array( 'VipInfinity', 'init' ) );