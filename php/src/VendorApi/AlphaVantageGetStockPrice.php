<?php

namespace Stocks\VendorApi;

use Stocks\CurlUrl;

class AlphaVantageGetStockPrice extends AlphaVantageBase {
	protected $symbol = null;
	protected $compact = false;

	public function __construct($_1) {
		$this->symbol = $_1['symbol'] ?? '';
		$this->compact = $_1['compact'] ?? false;
	}

	public function sync() {}

	public function getData() {
		$url = $this->getUrl();

		$rawData = (new CurlUrl($url))->data;
		$jsonData = json_decode($rawData);

		return $this->parseJson($jsonData);
	}

	private function getUrl(): string {
		if(!$this->symbol) {
			return '';
		}
		$size = $this->compact ? 'compact' : 'full';
		$url = $this->getFunctionUrl('TIME_SERIES_DAILY_ADJUSTED');
		$url .= "&symbol={$this->symbol}&outputsize={$size}";

		return $url;
	}

	public function parseJson($jsonData) {
		$error = $jsonData->{'Error Message'} ?? '';
		if($error) {
			return $error;
		}

		$output = [];

		$time_series = $jsonData->{'Time Series (Daily)'};

		if(!is_array($time_series) && !is_a($time_series, '\stdClass')) {
			return [];
		}

		foreach($time_series as $date => $time_series_item) {
			$open = $time_series_item->{'1. open'};
			$high = $time_series_item->{'2. high'};
			$low = $time_series_item->{'3. low'};
			$close = $time_series_item->{'4. close'};
			$adjustedClose = $time_series_item->{'5. adjusted close'};
			$volume = $time_series_item->{'6. volume'};
			$dividendAmount = $time_series_item->{'7. dividend amount'};
			$splitCoefficient = $time_series_item->{'8. split coefficient'};

			$output[] = [
				'date' => $date,
				'open' => $open,
				'high' => $high,
				'low' => $low,
				'close' => $close,
				'adjustedClose' => $adjustedClose,
				'volume' => $volume,
				'dividendAmount' => $dividendAmount,
				'splitCoefficient' => $splitCoefficient,
			];
		}

		return $output;
	}
}
