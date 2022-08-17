<?php

namespace Underpin\WordPress\Builders;

use Underpin\Exceptions\Invalid_Field;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\Object_Helper;
use Underpin\Interfaces\Can_Convert_To_Instance;
use Underpin\WordPress\Abstracts\Clause_Builder;
use WP_Meta_Query;

class Meta_Query_Builder extends Clause_Builder implements Can_Convert_To_Instance {

	protected array $clauses = [];

	public function __construct( protected string $instance = WP_Meta_Query::class ) {
	}

	public function set_parameters( Meta_Query_Parameter ...$parameters ): static {
		$parameters = Array_Helper::map( $parameters, fn ( Meta_Query_Parameter $parameter ) => $parameter->to_array() );

		$this->args = Array_Helper::merge( $this->args ?? [], $parameters );

		return $this;
	}

	/**
	 * @throws Invalid_Field
	 */
	public function to_instance(): WP_Meta_Query {
		$instance = Object_Helper::make_class( [
				'class' => $this->instance,
				'args'  => $this->to_array(),
			]
		);

		if ( $instance instanceof WP_Meta_Query ) {
			return $instance;
		} else {
			throw new Invalid_Field( message: 'Instance must be an instance of WP_Meta_Query.', data: [ 'received' => $this->instance ] );
		}
	}

}