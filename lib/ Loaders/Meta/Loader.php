<?php

namespace Underpin_WordPress\Meta;


use Underpin\Exceptions\Invalid_Registry_Item;
use Underpin\Exceptions\Unknown_Registry_Item;

abstract class Loader extends \Underpin_WordPress\Abstracts\Loader {

	/**
	 * @throws Unknown_Registry_Item
	 * @throws Invalid_Registry_Item
	 */
	public function __construct( Item ...$meta_types ) {
		parent::__construct( Item::class, ...$meta_types );
	}

	/**
	 * @param string $key
	 *
	 * @return Item
	 * @throws Unknown_Registry_Item
	 */
	public function get( string $key ): Item {
		return parent::get($key);
	}
}