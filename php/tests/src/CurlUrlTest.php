<?php

namespace StocksTest;

use PHPUnit\Framework\TestCase;
use phpmock\phpunit\PHPMock;

use Stocks\CurlUrl;

/**
 * @coversDefaultClass \Stocks\CurlUrl
 */
class CurlUrlTest extends TestCase {
	use PHPMock;

	/**
	 * @test
	 * @covers ::__construct()
	 */
	public function testConstruct() {
		$curl_exec = $this->getFunctionMock('Stocks', 'curl_exec');
		$curl_exec->expects($this->once())->willReturn('example curl result');
		$data = (new CurlUrl('https://pretend.url'))->data;
		$this->assertSame('example curl result', $data);
	}
}
