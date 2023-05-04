<?php

namespace StocksWp;

class Rest {
	public static function registerRoutes() {
		# GET stocks/v1/company-information/:stockId
		register_rest_route('stocks/v1', 'company-information/(?P<stockId>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\CompanyInformation\GetCompanyInformation::class, 'execute'],
		]);

		# POST stocks/v1/company-information/:stockId/sync
		register_rest_route('stocks/v1', 'company-information/(?P<stockId>\d+)/sync', [
			'methods' => 'POST',
			'callback' => [Rest\CompanyInformation\SyncCompanyInformation::class, 'execute'],
		]);

		# GET stocks/v1/cpi
		register_rest_route('stocks/v1', 'cpi', [
			'methods' => 'GET',
			'callback' => [Rest\Measure\Cpi\GetCpi::class, 'execute'],
		]);

		# GET stocks/v1/exchange
		register_rest_route('stocks/v1', 'exchange', [
			'methods' => 'GET',
			'callback' => [Rest\Exchange\GetExchanges::class, 'execute'],
		]);

		# GET stocks/v1/stats/all-time
		register_rest_route('stocks/v1', 'stats/all-time', [
			'methods' => 'GET',
			'callback' => [Rest\Stats\GetAllTimeStats::class, 'execute'],
		]);

		# GET stocks/v1/stock/get-listing
		register_rest_route('stocks/v1', 'stock/get-listing', [
			'methods' => 'GET',
			'callback' => [Rest\Stock\GetListing::class, 'execute'],
		]);

		#POST stocks/v1/stock/search
		register_rest_route('stocks/v1', 'stock/search', [
			'methods' => 'POST',
			'callback' => [Rest\Stock\Search::class, 'execute'],
		]);

		# GET stocks/v1/stock/:id
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Stock\GetStock::class, 'execute'],
		]);

		# GET stocks/v1/stock/:id/price
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)/price', [
			'methods' => 'GET',
			'callback' => [Rest\Stock\GetPrice::class, 'execute'],
		]);

		# GET stocks/v1/stock/:id/price/velocity
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)/price/velocity', [
			'methods' => 'GET',
			'callback' => [Rest\Stock\GetPriceVelocity::class, 'execute'],
		]);

		# POST stocks/v1/stock/:id/sync-price
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)/sync-price', [
			'methods' => 'POST',
			'callback' => [Rest\Stock\SyncPrice::class, 'execute']
		]);

		# GET stocks/v1/stock/:id/sma/:period
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)/price-sma/(?P<period>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Stock\GetPriceSma::class, 'execute'],
		]);

		# GET stocks/v1/stock/:id/warrant
		register_rest_route('stocks/v1', 'stock/(?P<id>\d+)/warrant', [
			'methods' => ['PATCH'],
			'callback' => [Rest\Stock\SaveAsWarrant::class, 'execute'],
			'permission_callback' => '__return_true',
		]);

		# GET stocks/v1/stock-share
		register_rest_route('stocks/v1', 'stock-share', [
			'methods' => 'GET',
			'callback' => [Rest\StockShare\GetStockShares::class, 'execute'],
		]);

		# POST stocks/v1/stock-share
		register_rest_route('stocks/v1', 'stock-share', [
			'methods' => 'POST',
			'callback' => [Rest\StockShare\CreateStockShare::class, 'execute'],
		]);

		# GET stocks/v1/roc/price/daily/:date/:metric/n/:number
		register_rest_route('stocks/v1', 'roc/price/daily/(?P<date>\d{4}-\d{2}-\d{2})/(?P<metric>[A-z]*)/n/(?P<number>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Roc\GetRocPriceDaily::class, 'execute']
		]);

		# GET stocks/v1/roc/sma/daily/:date/:metric/n/:number
		register_rest_route('stocks/v1', 'roc/sma/daily/(?P<date>\d{4}-\d{2}-\d{2})/(?P<metric>[A-z]*)/n/(?P<number>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Roc\GetRocSmaDaily::class, 'execute']
		]);

		# GET stocks/v1/roc/price/velocity/sma/daily/:date/:metric/n/:number
		register_rest_route('stocks/v1', 'roc/price/velocity/sma/daily/(?P<date>\d{4}-\d{2}-\d{2})/(?P<metric>[A-z]*)/n/(?P<number>\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Roc\GetPriceVelocitySmaDaily::class, 'execute']
		]);

		# GET stocks/v1/roc/volume/daily/:date/n/:number/abs-min/:absMin
		register_rest_route('stocks/v1', 'roc/volume/daily/(?P<date>\d{4}-\d{2}-\d{2})/n/(?P<number>\d+)/abs-min/(?P<absMin>\d*\.\d+)', [
			'methods' => 'GET',
			'callback' => [Rest\Roc\GetVolumeVelocityDaily::class, 'execute']
		]);
	}
}
