<?php

namespace StocksWp\Cli;

use Stocks\Service\SyncCompanyInformationService;

class SyncCompanyInformationCommand {
	/**
	 * Sync company information
	 *
	 * ## OPTIONS
	 *
	 * [--id=<id>]
	 * : The stock ID of the company to sync information for
	 *
	 * [--all]
	 * : Sync company information for all stocks
	 *
	 * [--start-id=<start-id>]
	 * : ID to start with if syncing all (syncing is done in increasing order of ID and ID's less than start-id will be skipped)
	 *
	 * ## EXAMPLES
	 *     wp stocks sync-company-information --id=123
	 *     wp stocks sync-company-information --all
	 */
	public static function execute($_1, $_2) {
		$id = $_2['id'] ?? 0;
		$syncAll = $_2['all'] ?? false;
		$startId = $_2['start-id'] ?? 0;

		if($syncAll) {
			ini_set('error_log', STOCKS_DIR . '/php_error.log');

			SyncCompanyInformationService::syncAll([
				'startId' => $startId,
			]);

			return;
		}

		if(!$id) {
			return;
		}

		$syncService = new SyncCompanyInformationService([
			'id' => $id,
			'refresh' => true,
		]);
		$syncService->sync();
	}
}
