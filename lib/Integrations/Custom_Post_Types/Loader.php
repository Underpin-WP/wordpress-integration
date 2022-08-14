<?php

namespace Underpin\WordPress\Integrations\Custom_Post_Types;

use Underpin\Exceptions\Invalid_Registry_Item;
use Underpin\Exceptions\Unknown_Registry_Item;
use Underpin\Factories\Object_Registry;

abstract class Loader extends \Underpin\WordPress\Abstracts\Loader {

	/**
	 * @throws Unknown_Registry_Item
	 * @throws Invalid_Registry_Item
	 */
	public function __construct( Item ...$post_types ) {
		parent::__construct( Item::class, ...$post_types );
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