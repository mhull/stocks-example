<?php

namespace Stocks;

class StringUtil {
	public static function toSlug($string): string {
		$string = preg_replace("/[ -]+/", '-', $string);
		$string = preg_replace("/[^\w-]/", '', $string);
		return strtolower($string);
	}
}
