<?php

namespace StocksWp\DataAccess;

use Stocks\Exchange\ExchangeFactory;
use StocksWp\Db;

class ExchangeAccess {
	public static function getWpPostByName($name) {
		$name = sanitize_text_field($name);

		$posts = get_posts([
			'post_type' => 'exchange',
			'title' => $name,
			'posts_per_page' => 1,
		]);

		return  $posts[0] ?? new \WP_Post(new \stdClass);
	}

	public static function insertItem($item) {
		$name = sanitize_text_field($item['name'] ?? '');

		return wp_insert_post([
			'post_type' => 'exchange',
			'post_title' => $name,
			'post_status' => 'publish',
		]);
	}
}
