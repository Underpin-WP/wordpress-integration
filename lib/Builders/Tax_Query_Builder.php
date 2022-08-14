<?php

namespace Underpin_WordPress\Builders;

use Underpin\Exceptions\Invalid_Field;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\Object_Helper;
use Underpin_WordPress\Abstracts\Clause_Builder;
use WP_Tax_Query;

class Tax_Query_Builder extends Clause_Builder {

	public function __construct( protected string $instance = WP_Tax_Query::class ) {
	}

	/**
	 * @throws Invalid_Field
	 */
	public function to_instance(): object {
		$instance = Object_Helper::make_class( [
				'class' => $this->instance,
				'args'  => $this->to_array(),
			]
		);

		if ( $instance instanceof WP_Tax_Query ) {
			return $instance;
		} else {
			throw new Invalid_Field( message: 'Query Instance must be an instance of WP_Tax_Query.', data: [ 'received' => $this->instance ] );
		}
	}

	public function set_parameters( Tax_Query_Parameter ...$parameters ): static {
		$parameters = Array_Helper::map( $parameters, fn ( Tax_Query_Parameter $parameter ) => $parameter->to_array() );

		$this->args['parameters'] = Array_Helper::merge( $this->args['parameters'] ?? [], $parameters );

		return $this;
	}

}