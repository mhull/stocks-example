<?php

namespace StocksTest;

use PHPUnit\Framework\TestCase;

use Stocks\Stock;

/**
 * @coversDefaultClass \Stocks\Stock
 */
class StockTest extends TestCase {
	/**
	 * @test
	 * @covers ::__construct()
	 */
	public function testConstruct() {
		$this->assertSame(0, (new Stock)->id);
		$this->assertSame(144, (new Stock(['id' => '144']))->id);
		$this->assertSame(0,   (new Stock(['id' => 'asd']))->id);

		$this->assertSame('', (new Stock)->name);
		$this->assertSame('ABC Corp.', (new Stock(['name' => 'ABC Corp.']))->name);

		$this->assertSame('', (new Stock)->symbol);
		$this->assertSame('ABC-123', (new Stock(['symbol' => 'ABC-123']))->symbol);

		$this->assertSame(0, (new Stock)->exchange);
		$this->assertSame(17, (new Stock(['exchange' => '17']))->exchange);

		$this->assertSame('', (new Stock)->assetType);
		$this->assertSame('ETF Ex. abc', (new Stock(['assetType' => 'ETF Ex. abc']))->assetType);

		$this->assertSame('', (new Stock)->ipoDate);
		$this->assertSame('1980-01-01', (new Stock(['ipoDate' => '1980-01-01']))->ipoDate);
	}
}
