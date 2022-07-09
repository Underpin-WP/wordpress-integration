<?php

namespace Underpin\WordPress\Abstracts;


use Underpin\Exceptions\Invalid_Registry_Item;
use Underpin\Exceptions\Unknown_Registry_Item;
use Underpin\Factories\Object_Registry;
use Underpin\Helpers\Processors\Array_Processor;
use Underpin\Helpers\Processors\List_Filter;
use Underpin\Interfaces\Queryable;
use Underpin\WordPress\Interfaces\Loader_Item;

abstract class Loader implements Queryable {

	protected Object_Registry $registry;

	/**
	 * @throws Unknown_Registry_Item
	 * @throws Invalid_Registry_Item
	 */
	public function __construct( $abstraction_class, Loader_Item ...$post_type ) {
		$this->registry = new Object_Registry( $abstraction_class );
		foreach ( func_get_args() as $post_type ) {
			/* @var Loader_Item $post_type */
			$this->registry->add( $post_type->get_id(), $post_type );
		}
	}

	/**
	 * @return Loader_Item
	 * @throws Unknown_Registry_Item
	 */
	public function get( string $key ): mixed {
		return $this->registry->get( $key );
	}

	public function to_array(): array {
		return $this->registry->to_array();
	}

	function query(): List_Filter {
		return $this->registry->query();
	}

}