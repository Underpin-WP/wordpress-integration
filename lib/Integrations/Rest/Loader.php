<?php

namespace Underpin\WordPress\Integrations\Rest;

use Underpin\Exceptions\Unknown_Registry_Item;
use Underpin\Registries\Rest_API_Loader;

class Loader extends \Underpin\Abstracts\Registries\Loader {

	public function __construct( $namespace, Rest_API_Loader ...$loaders ) {
		$items = [];
		foreach ( $loaders as $loader ) {
			foreach ( $loader->to_array() as $controller ) {
				$items[] = new Item( $controller, $namespace );
			}
		}

		parent::__construct( Item::class, ...$items );
	}

	/**
	 * @param string $key
	 *
	 * @return Item
	 * @throws Unknown_Registry_Item
	 */
	public function get( string $key ): Item {
		return parent::get( $key );
	}

}