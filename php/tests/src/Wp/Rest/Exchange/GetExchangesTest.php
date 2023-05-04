<?php

namespace StocksTest\Wp\Rest\Exchange;

use PHPUnit\Framework\TestCase;

use StocksWp\Rest\Exchange\GetExchanges as Route;

/**
 * @coversDefaultClass \StocksWp\Rest\Exchange\GetExchanges
 */
class GetExchangesTest extends TestCase {
	/**
	 * @test
	 * @covers ::execute()
	 */
	public function testExecute() {
		$post_1 = \Mockery::mock('\WP_Post');
		$post_1->ID = 333;
		$post_1->post_title = 'Ex. Titleee';

		\WP_Mock::userFunction('get_posts', [
			'times' => 1,
			'return' => [$post_1]
		]);

		$request = \Mockery::mock('\WP_REST_Request');
		$result = Route::execute($request);

		$this->assertSame($post_1->ID, $result[0]->id);
		$this->assertSame($post_1->post_title, $result[0]->name);
	}
}
