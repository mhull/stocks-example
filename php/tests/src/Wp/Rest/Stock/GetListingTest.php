<?php

namespace StocksTest\Wp\Rest\Stock;

use StocksTest\TestCases\WpMockTestCase;

use StocksWp\Rest\Stock\GetListing as Route;

/**
 * @coversDefaultClass \StocksWP\Rest\Stock\GetListing
 */
class GetListingTest extends WpMockTestCase {
	/**
	 * @test
	 * @covers ::execute()
	 */
	public function testExecute() {
		\WP_Mock::userFunction('get_posts', [
			'times' => 1,
			'return' => [],
		]);

		$this->assertSame( [], Route::execute());
	}
}
