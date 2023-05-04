<?php

namespace Stocks\StockShare;

use StocksWp\DataAccess\StockShareAccess;

class StockShareFactory {
	public static function createFromId($id) {
		return StockShareAccess::getById($id);
	}
}
