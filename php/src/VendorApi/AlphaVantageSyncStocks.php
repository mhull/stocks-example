<?php

namespace Stocks\VendorApi;

use Stocks\Csv;
use Stocks\CurlUrl;

class AlphaVantageSyncStocks extends AlphaVantageBase {
	public const LISTING_STATUS_CSV = STOCKS_DIR . '/data/csv/stock-listing.csv';
	protected const LISTING_STATUS_INACTIVE_CSV = STOCKS_DIR . '/data/csv/stock-listing-inactive.csv';

	public function sync($args = []) {
		$this->downloadActiveStocksCsv();
		$this->downloadInactiveStocksCsv();
	}

	public function getActiveStocksData() {
		$csv = $this->getActiveCsvContents();
		return (new Csv($csv))->toArray();
	}

	public function getInactiveStocksData() {
		$csv = $this->getInactiveCsvContents();
		return (new Csv($csv))->toArray();
	}

	protected function downloadActiveStocksCsv() {
		$url = $this->getFunctionUrl('LISTING_STATUS');
		file_put_contents(self::LISTING_STATUS_CSV, (new CurlUrl($url))->data);
	}

	protected function getActiveCsvContents() {
		return file_get_contents(self::LISTING_STATUS_CSV);
	}

	protected function downloadInactiveStocksCsv() {
		$url = $this->getInactiveStocksUrl();
		file_put_contents(self::LISTING_STATUS_INACTIVE_CSV, (new CurlUrl($url))->data);
	}

	protected function getInactiveStocksUrl() {
		return $this->getFunctionUrl('LISTING_STATUS') . '&state=delisted';
	}

	protected function getInactiveCsvContents() {
		return file_get_contents(self::LISTING_STATUS_INACTIVE_CSV);
	}
}
