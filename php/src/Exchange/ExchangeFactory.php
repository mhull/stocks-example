<?php

namespace Stocks\Exchange;

use Stocks\Exchange;
use StocksWp\DataAccess\ExchangeAccess;

class ExchangeFactory {
	public static function createFromWpPostId($postId) {
		$post = get_post($postId);
		if(!$post) {
			$post = new \WP_Post(new \stdClass);
		}

		return static::createFromWpPost($post);
	}

	/**
	 * @param \WP_Post $post
	 * @return Exchange
	 */
	public static function createFromWpPost(\WP_Post $post) {
		$properties = [
			'id' => $post->ID,
			'name' => $post->post_title,
		];
		return new Exchange($properties);
	}

	public static function createFromName($name) {
		$post = ExchangeAccess::getWpPostByName($name);
		return static::createFromWpPost($post);
	}
}
