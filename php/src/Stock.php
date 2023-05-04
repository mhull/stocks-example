<?php

namespace Stocks;

class Stock {
	public $id = 0;
	public $symbol = '';
	public $name = '';
	public $exchange = 0;
	public $assetType = '';
	public $ipoDate = '';
	public $delistingDate = '';
	public $active = false;

	public function __construct($_1 = null) {
		if(is_array($_1)) {
			if(isset($_1['id'])) {
				$this->id = (int) $_1['id'];
			}
			if(isset($_1['symbol'])) {
				$this->symbol = $_1['symbol'];
			}
			if(isset($_1['name'])) {
				$this->name = $_1['name'];
			}
			if(isset($_1['exchange'])) {
				$this->exchange = (int) $_1['exchange'];
			}
			if(isset($_1['assetType'])) {
				$this->assetType = $_1['assetType'];
			}
			if(isset($_1['ipoDate'])) {
				$this->ipoDate = $_1['ipoDate'];
			}
			if(isset($_1['delistingDate'])) {
				$this->delistingDate = $_1['delistingDate'];
			}
			if(isset($_1['active'])) {
				$this->active = (bool) $_1['active'];
			}
		}
	}
}
