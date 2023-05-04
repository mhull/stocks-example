<?php

namespace StocksWp\Rest\Measure\Cpi;

use StocksWp\DataAccess\CpiAccess;

class GetCpi {
	public static function execute() {
		return CpiAccess::getAll();
	}
}
