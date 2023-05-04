<?php

namespace StocksWp\Rest\StockShare;

use Stocks\Stock\StockFactory;
use Stocks\StockShare\StockShareFactory;
use StocksWp\DataAccess\StockShareAccess;

class CreateStockShare {
	public static function execute(\WP_REST_Request $request) {
		$symbol = strtoupper(sanitize_text_field($request->get_param('symbol')));
		if(!$symbol) {
			return false;
		}

		$stock = StockFactory::createFromSymbol($symbol);

		if(!$stock->id) {
			return false;
		}

		$share = [
			'stockId' => $stock->id,
			'numberShares' => $request->get_param('numberShares'),
			'datePurchased' => $request->get_param('datePurchased'),
			'purchasePrice' => $request->get_param('purchasePrice'),
		];

		$share_id = StockShareAccess::insertItem($share);

		return StockShareFactory::createFromId($share_id);
	}
}
