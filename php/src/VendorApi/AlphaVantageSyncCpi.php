<?php

namespace Stocks\VendorApi;

use Stocks\CurlUrl;

class AlphaVantageSyncCpi extends AlphaVantageBase {
	private const JSON_FILE_PATH = STOCKS_DIR . '/data/json/cpi.json';

	public function sync() {
		file_put_contents(static::JSON_FILE_PATH, $this->getApiResponse());
	}

	protected function getUrl() {
		return $this->getFunctionUrl('CPI');
	}

	protected function getApiResponse() {
		return (new CurlUrl($this->getUrl()))->data;
	}

	public function parseJsonData() {
		$raw_json = json_decode(file_get_contents(static::JSON_FILE_PATH));

		$cpi_data = $raw_json->data;

		return array_map(function($item) { return (array) $item; }, $cpi_data);
	}
}
