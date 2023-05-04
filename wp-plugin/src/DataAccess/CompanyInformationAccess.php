<?php

namespace StocksWp\DataAccess;

class CompanyInformationAccess {
	private const TABLE_NAME = 'stocks__company_information';

	public static function getTableName() {
		global $wpdb;
		return $wpdb->prefix . static::TABLE_NAME;
	}

	public static function syncTable() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$tableName = static::getTableName();

		$sql = "CREATE TABLE `{$tableName}` (
				`stockId` bigint(20) NOT NULL,
				`name` text NOT NULL,
				`symbol` tinytext NOT NULL,
				`latestQuarter` date NOT NULL,
				`assetType` tinytext NOT NULL,
				`description` text NOT NULL,
				`cik` bigint(20) COMMENT 'Central index key',
				`exchange` tinytext NOT NULL,
				`currency` tinytext NOT NULL,
				`country` tinytext NOT NULL,
				`sector` text NOT NULL,
				`industry` text NOT NULL,
				`address` text NOT NULL,
				`fiscalYearEnd` tinytext NOT NULL,
				`marketCap` bigint(20) COMMENT 'Share price times number of outstanding shares',
				`analystTargetPrice` float,
				`ebitda` bigint(20) COMMENT 'Earnings before interest, taxes, depreciation, and amortization',
				`eps` float COMMENT 'Price-earnings ratio (price divided by earnings per share)',
				`peRatio` float COMMENT 'Price-earnings ratio (price divided by earnings per share)',
				`pegRatio` float COMMENT 'Price-earnings to growth ratio (ideallybshould be < 1)',
				`bookValue` float,
				`dividendPerShare` float,
				`dividendYield` float COMMENT 'dividend per share divided by share price',
				`revenuePerShareTtm` float COMMENT 'trailing twelve months',
				`profitMargin` float COMMENT 'Net income divided by revenue',
				`operatingMarginTtm` float COMMENT 'Like profit margin, but factors in variable costs of production',
				`returnOnAssetsTtm` float COMMENT 'Net income divided by average assets',
				`returnOnEquityTtm` float COMMENT 'Net income divided by shareholder equity (assets minus liabilities)',
				`revenueTtm` bigint(20),
				`grossProfitTtm` bigint(20),
				`dilutedEpsTtm` float,
				`quarterlyEarningsGrowthYoy` float COMMENT 'Year over year - annual ROC',
				`quarterlyRevenueGrowthYoy` float,
				`trailingPeRatio` float COMMENT 'Uses earnings from trailing 12 months',
				`forwardPeRatio` float COMMENT 'Uses forecasted earnings - not actual',
				`priceToSalesRatioTtm` float,
				`priceToBookRatio` float COMMENT 'Stock price per share divided by book value per share',
				`evToRevenue` float COMMENT 'Enterprise-value to revenue. EV is a measure of a company\'s total value.',
				`evToEbitda` float,
				`beta` float COMMENT 'Measure of volatility compared to market as a whole (which has a value of 1). Greater than 1 means a stock is more volatile than the market itself',
				`annualHigh` float,
				`annualLow` float,
				`sma50Day` float,
				`sma200Day` float,
				`sharesOutstanding` bigint(20),
				`dividendDate` date,
				`exDividendDate` date,
				PRIMARY KEY (`stockId`)
			)
			ENGINE=InnoDB DEFAULT CHARSET=utf8";

		dbDelta($sql);
	}

	public static function getForStock($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		if(!$stockId) {
			return null;
		}

		$query = "SELECT * FROM `{$tableName}` WHERE `stockId`=%d LIMIT 1";
		$query = $wpdb->prepare($query, $stockId);

		return $wpdb->get_row($query) ?? new \stdClass;
	}

	public static function getLatestQuarter($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		if(!$stockId) {
			return '';
		}

		$query = "SELECT `latestQuarter`
			FROM {$tableName}
			WHERE `stockId`=%d";

		$query = $wpdb->prepare($query, $stockId);

		$latestQuarter = $wpdb->get_var($query);

		return $latestQuarter ?: '';
	}

	public static function saveItem($item) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($item['stockId'] ??  0);

		if(!$stockId) {
			return;
		}

		$itemLatestQuarter = sanitize_text_field($item['latestQuarter'] ?? '');
		if(!$itemLatestQuarter) {
			return;
		}

		$isUpdate = false;

		$savedLatestQuarter = static::getLatestQuarter($stockId);

		if($savedLatestQuarter) {
			if($itemLatestQuarter <= $savedLatestQuarter) {
				return;
			}

			$isUpdate = true;
		}

		$sanitizedData = [
			'name' => sanitize_text_field($item['name'] ?? ''),
			'symbol' => sanitize_text_field($item['symbol'] ?? ''),
			'latestQuarter' => $itemLatestQuarter,
			'assetType' => sanitize_text_field($item['assetType'] ?? ''),
			'description' => sanitize_text_field($item['description'] ?? ''),
			'cik' => absint($item['cik'] ?? 0) ?: null,
			'exchange' => sanitize_text_field($item['exchange'] ?? ''),
			'currency' => sanitize_text_field($item['currency'] ?? ''),
			'country' => sanitize_text_field($item['country'] ?? ''),
			'sector' => sanitize_text_field($item['sector'] ?? ''),
			'industry' => sanitize_text_field($item['industry'] ?? ''),
			'address' => sanitize_text_field($item['address'] ?? ''),
			'fiscalYearEnd' => sanitize_text_field($item['fiscalYearEnd'] ?? ''),
			'marketCap' => absint($item['marketCap'] ?? 0) ?: null,
			'analystTargetPrice' => floatval($item['analystTargetPrice'] ?? 0) ?: null,
			'ebitda' => absint($item['ebitda'] ?? 0) ?: null,
			'eps' => floatval($item['eps'] ?? 0) ?: null,
			'peRatio' => floatval($item['peRatio'] ?? 0) ?: null,
			'pegRatio' => floatval($item['pegRatio'] ?? 0) ?: null,
			'bookValue' => floatval($item['bookValue'] ?? 0) ?: null,
			'dividendPerShare' => floatval($item['dividendPerShare'] ?? 0) ?: null,
			'dividendYield' => floatval($item['dividendYield'] ?? 0) ?: null,
			'revenuePerShareTtm' => floatval($item['revenuePerShareTtm'] ?? 0) ?: null,
			'profitMargin' => floatval($item['profitMargin'] ?? 0) ?: null,
			'operatingMarginTtm' => floatval($item['operatingMarginTtm'] ?? 0) ?: null,
			'returnOnAssetsTtm' => floatval($item['returnOnAssetsTtm'] ?? 0) ?: null,
			'returnOnEquityTtm' => floatval($item['returnOnEquityTtm'] ?? 0) ?: null,
			'revenueTtm' => absint($item['revenueTtm'] ?? 0) ?: null,
			'grossProfitTtm' => absint($item['grossProfitTtm'] ?? 0) ?: null,
			'dilutedEpsTtm' => floatval($item['dilutedEpsTtm'] ?? 0) ?: null,
			'quarterlyEarningsGrowthYoy' => floatval($item['quarterlyEarningsGrowthYoy'] ?? 0) ?: null,
			'quarterlyRevenueGrowthYoy' => floatval($item['quarterlyRevenueGrowthYoy'] ?? 0) ?: null,
			'trailingPeRatio' => floatval($item['trailingPeRatio'] ?? 0) ?: null,
			'forwardPeRatio' => floatval($item['forwardPeRatio'] ?? 0) ?: null,
			'priceToSalesRatioTtm' => floatval($item['priceToSalesRatioTtm'] ?? 0) ?: null,
			'priceToBookRatio' => floatval($item['priceToBookRatio'] ?? 0) ?: null,
			'evToRevenue' => floatval($item['evToRevenue'] ?? 0) ?: null,
			'evToEbitda' => floatval($item['evToEbitda'] ?? 0) ?: null,
			'beta' => floatval($item['beta'] ?? 0) ?: null,
			'annualHigh' => floatval($item['annualHigh'] ?? 0) ?: null,
			'annualLow' => floatval($item['annualLow'] ?? 0) ?: null,
			'sma50Day' => floatval($item['sma50Day'] ?? 0) ?: null,
			'sma200Day' => floatval($item['sma200Day'] ?? 0) ?: null,
			'sharesOutstanding' => absint($item['sharesOutstanding'] ?? 0) ?: null,
			'dividendDate' => sanitize_text_field($item['dividendDate'] ?? '') ?: null,
			'exDividendDate' => sanitize_text_field($item['exDividendDate'] ??'') ?: null,
		];

		if(strtolower($sanitizedData['dividendDate']) === 'none') {
			$sanitizedData['dividendDate'] = null;
		}

		if(strtolower($sanitizedData['exDividendDate']) === 'none') {
			$sanitizedData['exDividendDate'] = null;
		}

		$formats = [
			'%s', // name
			'%s', // symbol
			'%s', // latestQuarter
			'%s', // assetType
			'%s', // description
			'%d', // cik
			'%s', // exchange
			'%s', // currency
			'%s', // country
			'%s', // sector
			'%s', // industry
			'%s', // address
			'%s', // fiscalYearEnd
			'%d', // marketCap
			'%s', // analystTargetPrice
			'%d', // ebitda
			'%s', // eps
			'%s', // peRatio
			'%s', // pegRatio
			'%s', // bookValue
			'%s', // dividendPerShare
			'%s', // dividendYield
			'%s', // revenuePerShareTtm
			'%s', // profitMargin
			'%s', // operatingMarginTtm
			'%s', // returnOnAssetsTtm
			'%s', // returnOnEquityTtm
			'%d', // revenueTtm
			'%d', // grossProfitTtm
			'%s', // dilutedEpsTtm
			'%s', // quarterlyEarningsGrowthYoy
			'%s', // quarterlyRevenueGrowthYoy
			'%s', // trailingPeRatio
			'%s', // forwardPeRatio
			'%s', // priceToSalesRatioTtm
			'%s', // priceToBookRatio
			'%s', // evToRevenue
			'%s', // evToEbitda
			'%s', // beta
			'%s', // annualHigh
			'%s', // annualLow
			'%s', // sma50Day
			'%s', // sma200Day
			'%d', // sharesOutstanding
			'%s', // dividendDate
			'%s', // exDividendDate
		];

		if($isUpdate) {
			$wpdb->update(
				$tableName,
				$sanitizedData,
				['stockId' => $stockId],
				$formats,
				'%d'
			);
		} else {
			$sanitizedData['stockId'] = $stockId;
			$formats[] = '%d';

			$wpdb->insert(
				$tableName,
				$sanitizedData,
				$formats,
			);
		}
	}

	public static function deleteItem($stockId) {
		global $wpdb;
		$tableName = static::getTableName();

		$stockId = absint($stockId);

		if(!$stockId) {
			return;
		}

		$wpdb->delete(
			$tableName,
			['stockId' => $stockId],
			'%d'
		);
	}
}
