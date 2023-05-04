<?php

namespace StocksTest;

use PHPUnit\Framework\TestCase;

use Stocks\Exchange;

/**
 * @coversDefaultClass \Stocks\Exchange
 */
class ExchangeTest extends TestCase{
	/**
	 * @test
	 * @covers ::__construct()
	 */
	public function testConstruct() {
		$this->assertSame(0, (new Exchange)->id);
		$this->assertSame(144, (new Exchange(['id' => '144']))->id);
		$this->assertSame(0,   (new Exchange(['id' => 'asd']))->id);

		$this->assertSame('', (new Exchange)->name);

		$exchange = (new Exchange(['name' => 'Ex. Exchange']));
		$this->assertSame('Ex. Exchange', $exchange->name);
	}

	/**
	 * @test
	 * @covers ::setSlug()
	 */
	public function testSetSlug() {
		$exchange = new Exchange(['name' => 'Ex. Thing! - 123']);
		$this->assertSame('ex-thing-123', $exchange->getSlug());
	}


}
