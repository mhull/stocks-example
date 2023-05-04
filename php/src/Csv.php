<?php

namespace Stocks;

class Csv {
	public $string = '';

	public function __construct($_1 = null) {
		if(is_string($_1)) {
			$this->string = $_1;
		}
	}

	public function toArray(): array {
		if(empty($this->string)) {
			return [];
		}
		$csv_rows = str_getcsv($this->string, "\n");
		$headers = str_getcsv(array_shift($csv_rows));

		$array_output = [];

		foreach($csv_rows as $csv_row) {
			$cols = str_getcsv($csv_row);
			$new_item = [];

			foreach($headers as $index => $header) {
				$new_item[$header] = $cols[$index];
			}

			$array_output[] = $new_item;
		}

		return $array_output;
	}
}
