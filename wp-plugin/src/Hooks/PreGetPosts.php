<?php

namespace StocksWp\Hooks;

class PreGetPosts {
	public static function main($query) {
		$static = new static;

		$static->defaultSortStockPosts($query);
		$static->defaultSortExchangePosts($query);
	}

	public function defaultSortStockPosts($query) {
		if(!$query->is_main_query()) {
			return;
		}
		if('stock' !== $query->get('post_type')) {
			return;
		}

		$query->set('order', 'asc');
		$query->set('orderby', 'title');
	}

	public function defaultSortExchangePosts($query) {
		if(!$query->is_main_query()) {
			return;
		}
		if('exchange' !== $query->get('post_type')) {
			return;
		}

		$query->set('order', 'asc');
		$query->set('orderby', 'title');
	}
}
