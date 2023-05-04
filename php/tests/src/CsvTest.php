<?php

namespace StocksTest;

use PHPUnit\Framework\TestCase;
use Stocks\Csv;

/**
 * Class CsvTest
 * @package Stocks\Tests
 * @coversDefaultClass \Stocks\Csv
 */
class CsvTest extends TestCase {
	/**
	 * @test
	 * @covers ::__construct()
	 */
	public function testConstruct() {
		$sample_csv = "name, phone\nMichael,555-2323";
		$csv = new Csv($sample_csv);
		$this->assertSame($sample_csv, $csv->string);
	}
}
