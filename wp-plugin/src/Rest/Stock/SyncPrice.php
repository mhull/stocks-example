<?php

namespace StocksWp\Rest\Stock;

use Stocks\Service\SyncStockPriceAccelerationService;
use Stocks\Service\SyncStockPriceService;
use Stocks\Service\SyncStockPriceVelocityService;
use Stocks\Service\SyncStockPriceVelocitySmaService;
use Stocks\Service\SyncStockPriceSmaService;
use Stocks\Service\SyncStockSmaVelocityService;
use StocksWp\DataAccess\StockPriceAccess;

class SyncPrice {
	public static function execute(\WP_REST_Request $request) {
		$stockId = absint($request->get_param('id'));
		$number = StockPriceAccess::countItems($stockId);

		if(!$number) {
			$number = 200;
		}

		// Sync stock price
		$priceService = new SyncStockPriceService([
			'id' => $stockId,
			'number' => $number,
			// Always refresh when syncing stock price via REST request
			'refresh' => true,
		]);
		$priceService->sync();

		// Sync stock price velocity
		$velocityService = new SyncStockPriceVelocityService([
			'id' => $stockId,
			'refresh' => true,
		]);
		$velocityService->sync();

		// Sync stock price acceleration
		$accelerationService = new SyncStockPriceAccelerationService([
			'id' => $stockId,
			'refresh' => true,
		]);
		$accelerationService->sync();

		// Sync stock price SMA 100
		$smaService100 = new SyncStockPriceSmaService([
			'id' => $stockId,
			'refresh' => true,
			'period' => 100
		]);
		$smaService100->sync();

		// Sync stock price SMA 10
		$smaService10 = new SyncStockPriceSmaService([
			'id' => $stockId,
			'refresh' => true,
			'period' => 10
		]);
		$smaService10->sync();

		// Sync stock price SMA velocity
		$smaVelocityService = new SyncStockSmaVelocityService([
			'id' => $stockId,
			'refresh' => true,
		]);
		$smaVelocityService->sync();

		// Sync stock price velocity SMA
		$velocitySmaService = new SyncStockPriceVelocitySmaService([
			'id' => $stockId,
			'refresh' => true,
		]);
		$velocitySmaService->sync();

		return StockPriceAccess::getItems($stockId);
	}
}
