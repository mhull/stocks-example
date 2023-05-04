<?php

namespace StocksTest\VendorApi;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Stocks\VendorApi\AlphaVantageSyncStocks
 */
class AlphaVantageSyncStocksTest extends TestCase {
	/**
	 * @test
	 * @covers ::sync()
	 */
	public function testSync() {
		$mock = $this->getMockBuilder('Stocks\VendorApi\AlphaVantageSyncStocks')
			->onlyMethods(['getListingStatus'])
			->getMock();

		$mock->expects($this->once())
			->method('getListingStatus');

		$mock->sync();
	}
}
