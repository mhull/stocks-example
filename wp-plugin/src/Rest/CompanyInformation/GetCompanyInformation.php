<?php

namespace StocksWp\Rest\CompanyInformation;

use StocksWp\DataAccess\CompanyInformationAccess;

class GetCompanyInformation {
	public static function execute(\WP_REST_Request $request) {
		$stockId = absint($request->get_param('stockId'));

		if(!$stockId) {
			return new \stdClass;
		}

		return CompanyInformationAccess::getForStock($stockId);
	}
}
