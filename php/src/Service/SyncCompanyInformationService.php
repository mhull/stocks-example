<?php

namespace Stocks\Service;

use Stocks\VendorApi\AlphaVantageGetCompanyInformation;
use StocksWp\DataAccess\CompanyInformationAccess;
use StocksWp\DataAccess\StockAccess;

class SyncCompanyInformationService {
	private $id = 0;
	private $symbol = '';
	private $refresh = false;
	private $apiService = null;

	public function __construct($_1) {
		$this->id = absint($_1['id'] ?? 0);
		$this->symbol = StockAccess::getSymbolById($this->id);
		$this->refresh = (bool) ($_1['refresh'] ?? false);

		$this->apiService = new AlphaVantageGetCompanyInformation([
			'symbol' => $this->symbol,
		]);
	}

	public function sync() {
		if(!$this->id || !$this->symbol) {
			return;
		}

		if($this->refresh) {
			CompanyInformationAccess::deleteItem($this->id);
		}

		$companyInformation = $this->apiService->getData();

		if(is_string($companyInformation)) {
			error_log('Issue with company information: ' . $this->symbol . ' (' . $this->id . ') : ' . $companyInformation);
			return;
		}

		$companyInformation['stockId'] = $this->id;

		CompanyInformationAccess::saveItem($companyInformation);
	}

	public static function syncAll($_1) {
		$startId = (int) ($_1['startId'] ?? 0);

		$stockIds = StockAccess::getAllIds();

		foreach($stockIds as $stockId) {

			if($startId && $stockId < $startId) {
				continue;
			}

			$assetType = get_post_meta($stockId, 'assetType', true);

			// ETF's don't have company information
			if(strtolower($assetType) === 'etf') {
				continue;
			}

			echo PHP_EOL . "Syncing company information: {$stockId}" . PHP_EOL;

			$static = new static([
				'id' => $stockId,
			]);
			$static->sync();
			$static->apiService->sleep();
		}
	}
}
