<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncCpiService;

class SyncCpiCommand {
	public static function execute($_1, $_2) {
		echo "Syncing CPI data..\n";

		$syncService = new SyncCpiService;
		$syncService->sync();
	}
}
