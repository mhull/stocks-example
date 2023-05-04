<?php

namespace Stocks;

class NumberSet {
	/** @var array */
	private $numbers;

	public function __construct($_1) {
		$this->numbers = $_1['numbers'] ?? [];
	}

	public function getMean() {
		if(!count($this->numbers)) {
			return null;
		}

		return array_sum($this->numbers)/count($this->numbers);
	}

	public function getMedian() {
		if(!$this->numbers) {
			return null;
		}

		$numbers = $this->numbers;

		sort($numbers);

		/**
		 * For an even amount of numbers, average the middle two numbers for the result
		 * 10 => 0 1 2 3 4 5 6 7 8 9
		 *
		 * 4 = (length/2) - 1
		 * 5 = (length/2)
		 *   --> average(4,5)
		 *
		 * For an odd amount of numbers, return the middle number
		 * 5 => 0 1 2 3 4
		 *
		 * 2 = (length-1) / 2
		 *   --> 2
		 */

		$count = count($numbers);

		// For an even amount of numbers, average the middle two numbers for the result
		if($count % 2 === 0) {
			$index1 = (int) (($count/2) - 1);
			$index2 = (int) $count/2;

			return ($numbers[$index1] + $numbers[$index2]) / 2;
		}

		// For an odd amount of numbers, return the middle number
		$index = (int)(($count - 1)/2);
		return $numbers[$index];
	}

	public function getStdDev() {
		if(count($this->numbers) < 2) {
			return null;
		}

		$mean = $this->getMean();

		$sqDeviations = array_map(function($number) use ($mean) {
			return pow($number - $mean, 2);
		}, $this->numbers);

		return sqrt(array_sum($sqDeviations)/(count($this->numbers)));
	}
}
