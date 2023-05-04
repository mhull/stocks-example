<?php

namespace Stocks\VendorApi;

abstract class AlphaVantageBase implements VendorApiInterface {
	protected const API_KEY = ''; // Enter API Key here
	protected const API_BASE_URL = 'https://www.alphavantage.co/query';

	/**
	 * Number of microseconds to wait between requests, in order to avoid API rate limits
	 * @var int
	 */
	public const API_MIN_REQUEST_TIME = 250000;

	protected function getFunctionUrl($function): string {
		$url = static::API_BASE_URL;
		$url .= "?function={$function}";
		$url .= '&apikey=' . static::API_KEY;

		return $url;
	}

	public function sleep($microseconds = null) {
		if(!$microseconds) {
			$microseconds = $this::API_MIN_REQUEST_TIME;
		}

		usleep($microseconds);
	}
}
