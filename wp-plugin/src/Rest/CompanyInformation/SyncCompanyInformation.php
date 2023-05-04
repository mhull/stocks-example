<?php

namespace StocksWp\Rest\CompanyInformation;

use Stocks\Service\SyncCompanyInformationService;

class SyncCompanyInformation {
	public static function execute(\WP_REST_Request $request) {
		$stockId = absint($request->get_param('stockId'));

		if(!$stockId) {
			return;
		}

		$syncService = new SyncCompanyInformationService([
			'id' => $stockId,
			'refresh' => true,
		]);
		$syncService->sync();
	}
}
