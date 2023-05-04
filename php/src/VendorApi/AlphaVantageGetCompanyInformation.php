<?php

namespace Stocks\VendorApi;

use Stocks\CurlUrl;

class AlphaVantageGetCompanyInformation extends AlphaVantageBase {
	private $symbol;

	public function __construct($_1) {
		$this->symbol = $_1['symbol'] ?? '';
	}

	public function sync() {}

	public function getData() {
		$url = $this->getUrl();

		$rawData = (new CurlUrl($url))->data;
		$jsonData = json_decode($rawData);

		return $this->parseJson($jsonData);
	}

	private function parseJson($jsonData) {
		$error = $jsonData->{'Error Message'} ?? '';
		if($error) {
			return $error;
		}

		return [
			'symbol' => $jsonData->{'Symbol'} ?? '',
			'name' => $jsonData->{'Name'} ?? '',
			'assetType' => $jsonData->{'AssetType'} ?? '',
			'description' => $jsonData->{'Description'} ?? '',
			'cik' => $jsonData->{'CIK'} ?? '',
			'exchange' => $jsonData->{'Exchange'} ?? '',
			'currency' => $jsonData->{'Currency'} ?? '',
			'country' => $jsonData->{'Country'} ?? '',
			'sector' => $jsonData->{'Sector'} ?? '',
			'industry' => $jsonData->{'Industry'} ?? '',
			'address' => $jsonData->{'Address'} ?? '',
			'fiscalYearEnd' => $jsonData->{'FiscalYearEnd'} ?? '',
			'latestQuarter' => $jsonData->{'LatestQuarter'} ?? '',
			'analystTargetPrice' => $jsonData->{'AnalystTargetPrice'} ?? '',
			'marketCap' => $jsonData->{'MarketCapitalization'} ?? '',
			'ebitda' => $jsonData->{'EBITDA'} ?? '',
			'eps' => $jsonData->{'EPS'} ?? '',
			'peRatio' => $jsonData->{'PERatio'} ?? '',
			'pegRatio' => $jsonData->{'PEGRatio'} ?? '',
			'bookValue' => $jsonData->{'BookValue'} ?? '',
			'dividendPerShare' => $jsonData->{'DividendPerShare'} ?? '',
			'dividendYield' => $jsonData->{'DividendYield'} ?? '',
			'revenuePerShareTtm' => $jsonData->{'RevenuePerShareTTM'} ?? '',
			'profitMargin' => $jsonData->{'ProfitMargin'} ?? '',
			'operatingMarginTtm' => $jsonData->{'OperatingMarginTTM'} ?? '',
			'returnOnAssetsTtm' => $jsonData->{'ReturnOnAssetsTTM'} ?? '',
			'returnOnEquityTtm' => $jsonData->{'ReturnOnEquityTTM'} ?? '',
			'revenueTtm' => $jsonData->{'RevenueTTM'} ?? '',
			'grossProfitTtm' => $jsonData->{'GrossProfitTTM'} ?? '',
			'dilutedEpsTtm' => $jsonData->{'DilutedEPSTTM'} ?? '',
			'quarterlyEarningsGrowthYoy' => $jsonData->{'QuarterlyEarningsGrowthYOY'} ?? '',
			'quarterlyRevenueGrowthYoy' => $jsonData->{'QuarterlyRevenueGrowthYOY'} ?? '',
			'trailingPeRatio' => $jsonData->{'TrailingPE'} ?? '',
			'forwardPeRatio' => $jsonData->{'ForwardPE'} ?? '',
			'priceToSalesRatioTtm' => $jsonData->{'PriceToSalesRatioTTM'} ?? '',
			'priceToBookRatio' => $jsonData->{'PriceToBookRatio'} ?? '',
			'evToRevenue' => $jsonData->{'EVToRevenue'} ?? '',
			'evToEbitda' => $jsonData->{'EVToEBITDA'} ?? '',
			'beta' => $jsonData->{'Beta'} ?? '',
			'annualHigh' => $jsonData->{'52WeekHigh'} ?? '',
			'annualLow' => $jsonData->{'52WeekLow'} ?? '',
			'sma50Day' => $jsonData->{'50DayMovingAverage'} ?? '',
			'sma200Day' => $jsonData->{'200DayMovingAverage'} ?? '',
			'sharesOutstanding' => $jsonData->{'SharesOutstanding'} ?? '',
			'dividendDate' => $jsonData->{'DividendDate'} ?? '',
			'exDividendDate' => $jsonData->{'ExDividendDate'} ?? '',
		];
	}

	private function getUrl() {
		return $this->getFunctionUrl('OVERVIEW') .
		'&symbol=' . $this->symbol;
	}
}
