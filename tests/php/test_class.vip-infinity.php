<?php

require_once dirname( __FILE__ ) . '/../../class.vip-infinity.php';

class WP_Test_VipInfinity extends WP_UnitTestCase {
	function test_init_does_something() {
		VipInfinity::init();
	}
}