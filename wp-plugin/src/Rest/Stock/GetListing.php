<?php

namespace StocksWp\Rest\Stock;

use Stocks\Stock\StockFactory;

class GetListing {
	public static function execute() {
		$posts = get_posts([
			'post_type' => 'stock',
			'posts_per_page' => 10,
//			'order' => 'ASC',
			'orderby' => 'rand',
		]);

		return array_map(function($post) {
			return StockFactory::createFromWpPost($post);
		}, $posts);
	}
}
