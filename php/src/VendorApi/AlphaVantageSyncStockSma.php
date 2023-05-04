<?php

namespace Stocks\VendorApi;

use Stocks\CurlUrl;

class AlphaVantageSyncStockSma extends AlphaVantageBase {
	private $symbol = '';

	public function __construct($_1) {
		$this->symbol = $_1['symbol'] ?? '';
	}

	public function sync() {}

	public function getData() {
		$url = $this->getUrl();

		$rawData = (new CurlUrl($url))->data;
		$jsonData = json_decode($rawData);

		return $this->parseJsonData($jsonData);
	}

	private function getUrl() {
		return $this->getFunctionUrl('SMA') .
			"&symbol={$this->symbol}" .
			'&interval=daily&time_period=100&series_type=close';
	}

	private function parseJsonData($jsonData) {
		$error = $jsonData->{'Error Message'} ?? '';
		if($error) {
			return $error;
		}

		$data = [];

		$smaItems = $jsonData->{'Technical Analysis: SMA'};

		if(!is_array($smaItems) && !is_a($smaItems, '\stdClass')) {
			return [];
		}

		foreach($smaItems as $date => $smaItem) {
			$data[] = [
				'date' => $date,
				'value' => $smaItem->SMA,
			];
		}

		return $data;
	}
}
