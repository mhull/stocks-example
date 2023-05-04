<?php

namespace Stocks\Stock;

use Stocks\Stock;
use StocksWp\DataAccess\StockAccess;

class StockFactory {
	public static function getAll() {
		$posts = StockAccess::getAll();

		return array_map(function($post) {
			return static::createFromWpPost($post);
		}, $posts);
	}

	public static function createFromSymbol($symbol) {
		return static::createFromWpPostMeta(['symbol' => $symbol]);
	}

	public static function createFromWPPostId($postId) {
		$post = get_post($postId);
		if(!$post) {
			$post = new \WP_Post(new \stdClass);
		}

		return static::createFromWpPost($post);
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return Stock
	 */
	public static function createFromWpPost(\WP_Post $post): Stock {
		$properties = [
			'id' => $post->ID,
			'name' => $post->post_title,
			'symbol' => get_post_meta($post->ID, 'symbol', true),
			'exchange' => get_post_meta($post->ID, 'exchange', true),
			'assetType' => get_post_meta($post->ID, 'assetType', true),
			'ipoDate' => get_post_meta($post->ID, 'ipoDate', true),
			'delistingDate' => get_post_meta($post->ID, 'delistingDate', true),
			'active' => get_post_meta($post->ID, 'active', true),
		];

		return new Stock($properties);
	}

	public static function createFromWpPostMeta($post_meta = []) {
		$symbol = sanitize_text_field($post_meta['symbol'] ?? '');
		$exchange = absint($post_meta['exchange'] ?? 0);
		$ipoDate = sanitize_text_field($post_meta['ipoDate'] ?? '');

		$meta_query = [
			'relation' => 'AND',
		];

		if($symbol) {
			$meta_query[] = [
				'key' => 'symbol',
				'value' => $symbol,
				'compare' => '=',
			];
		}

		if($exchange) {
			$meta_query[] = [
				'key' => 'exchange',
				'value' => $exchange,
				'compare' => '=',
			];
		}

		if($ipoDate) {
			$meta_query[] = [
				'key' => 'ipoDate',
				'value' => $ipoDate,
				'compare' => '=',
			];
		}

		$stock_posts = get_posts([
			'post_type' => 'stock',
			'meta_query' => $meta_query,
			'posts_per_page' => 1,
		]);

		$stock_post = $stock_posts[0] ?? new \WP_Post(new \stdClass);

		return static::createFromWpPost($stock_post);
	}
}
