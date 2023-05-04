<?php

namespace StocksWp\Rest\Exchange;

use Stocks\Exchange\ExchangeFactory;

class GetExchanges {
	/**
	 * @param \WP_REST_Request $request
	 * @return array|\Stocks\Exchange[]
	 */
	public static function execute(\WP_REST_Request $request) {
		$posts = get_posts([
			'post_type' => 'exchange',
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1,
		]);

		return array_map(function($post) {
			return ExchangeFactory
				::createFromWpPost($post)
				->toStdClass();
		}, $posts);
	}
}
