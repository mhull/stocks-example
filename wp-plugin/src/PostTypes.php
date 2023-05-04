<?php

namespace StocksWp;

use StocksWp\PostTypes\ExchangePostType;
use StocksWp\PostTypes\StockPostType;

class PostTypes {
	public static function register() {
		StockPostType::register();
		ExchangePostType::register();
	}
}
