<?php

namespace Stocks\Service;

use StocksWp\DataAccess\CpiAccess;
use Stocks\VendorApi\AlphaVantageSyncCpi;

class SyncCpiService {
	private $alphaVantageApiService = null;

	public function __construct() {
		$this->alphaVantageApiService = new AlphaVantageSyncCpi;
	}

	public function sync() {
		$this->downloadJson();
		$this->updateDatabase();
	}

	private function downloadJson() {
		$this->alphaVantageApiService->sync();
	}

	private function updateDatabase() {
		$latest_date = CpiAccess::getLatestDate();

		$data = $this->alphaVantageApiService->parseJsonData();

		foreach($data as $item) {
			if($latest_date && $item['date'] <= $latest_date) {
				continue;
			}

			CpiAccess::insertItem($item);
		}
	}
}
