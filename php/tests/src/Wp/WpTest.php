<?php

namespace StocksTest\Wp;

use StocksTest\TestCases\WpMockTestCase;

class WpTest extends WpMockTestCase {
	/**
	 * @test
	 */
	public function testWpExists() {
		\WP_Mock::userFunction('wp', [
			'times' => 1,
		]);

		$this->wp();
		$this->assertTrue(function_exists('wp'));
	}

	private function wp() {
		wp();
	}
}
