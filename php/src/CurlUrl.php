<?php

namespace Stocks;

class CurlUrl {
	public $data = '';

	public function __construct($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$this->data = curl_exec($ch);
		curl_close($ch);
	}
}
