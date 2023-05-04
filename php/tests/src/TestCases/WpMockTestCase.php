<?php

namespace StocksTest\TestCases;

use WP_Mock\Tools\TestCase;

abstract class WpMockTestCase extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
