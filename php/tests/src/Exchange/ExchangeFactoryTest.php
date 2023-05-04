<?php

namespace StocksTest\Exchange;

use PHPUnit\Framework\TestCase;

use Stocks\Exchange\ExchangeFactory;


/**
 * @coversDefaultClass \Stocks\Exchange\ExchangeFactory
 */
class ExchangeFactoryTest extends TestCase {
	/**g
	 * @test
	 * @covers ::createFromWpPost()
	 */
	public function testCreateFromWpPost() {
		$post = \Mockery::mock('\WP_Post');

		$post->ID = 123;
		$post->post_title = 'Example post';


		$exchange = ExchangeFactory::createFromWpPost($post);

		$this->assertSame($exchange->id, $post->ID);
		$this->assertSame($exchange->name, $post->post_title);
	}
}
