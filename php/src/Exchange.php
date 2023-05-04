<?php

namespace Stocks;

use Stocks\StringUtil;

class Exchange {
	public $id = 0;
	public $name = '';

	private $slug = null;

	public function __construct($_1 = null) {
		if(is_array($_1)) {
			if(isset($_1['id'])) {
				$this->id = (int) $_1['id'];
			}
			if(isset($_1['name'])) {
				$this->name = $_1['name'];
			}
		}
	}

	public function getSlug() {
		if(null === $this->slug) {
			$this->setSlug();
		}
		return $this->slug;
	}

	public function setSlug() {
		$this->slug = StringUtil::toSlug($this->name);
	}

	public function toStdClass() {
		$std_class = new \stdClass;

		$std_class->id = $this->id;
		$std_class->name = $this->name;
		$std_class->slug = $this->getSlug();
		return $std_class;
	}
}
