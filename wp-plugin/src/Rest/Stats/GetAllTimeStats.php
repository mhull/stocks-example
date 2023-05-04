<?php

namespace StocksWp\Rest\Stats;

use Stocks\Stock\StockFactory;
use StocksWp\DataAccess\StockPriceAccess;

class GetAllTimeStats {
	public static function execute() {
		$maxClosingPriceItems = StockPriceAccess::getItemsByMaxClosingPrice();
		$minClosingPriceItems = StockPriceAccess::getItemsByMinClosingPrice();

		$map_item = function($item) {
			$stock = StockFactory::createFromWPPostId($item->stock_id);

			return [
				'stock' => [
					'id' => $item->stock_id,
					'symbol' => $stock->symbol,
				],
				'amount' => $item->close,
				'date' => $item->date,
			];
		};

		$maxItems = array_map(function($item) use ($map_item) {
			$item = $map_item($item);
			$item['type'] = 'high';
			return $item;
		}, $maxClosingPriceItems);

		$minItems = array_map(function($item) use ($map_item) {
			$item = $map_item($item);
			$item['type'] = 'low';
			return $item;
		}, $minClosingPriceItems);

		return array_merge($maxItems, $minItems);
	}
}
