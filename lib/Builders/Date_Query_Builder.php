<?php

namespace Underpin\WordPress\Builders;

use Underpin\Exceptions\Invalid_Field;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\Object_Helper;
use Underpin\WordPress\Abstracts\Clause_Builder;
use Underpin\WordPress\Enums\Compare;
use Underpin\WordPress\Enums\Database_Operator;
use Underpin\WordPress\Enums\Date_Query_Columns;
use UnitEnum;
use WP_Date_Query;

class Date_Query_Builder extends Clause_Builder {

	protected string $default_column = 'post_date';

	public function __construct( protected string $instance = WP_Date_Query::class ) {
	}

	public function set_default_column( Date_Query_Columns|UnitEnum $column ): static {
		$this->default_column = $column->name;

		return $this;
	}

	public function set_parameters( Date_Query_Parameter ...$parameters ): static {
		$parameters = Array_Helper::map( $parameters, fn ( Date_Query_Parameter $parameter ) => $parameter->to_array() );

		$this->args['parameters'] = Array_Helper::merge( $this->args['parameters'] ?? [], $parameters );

		return $this;
	}

	public function set_column( Date_Query_Columns|UnitEnum $column ): static {
		return $this->set_string( 'column', $column->name );
	}

	public function set_compare( Database_Operator|Compare $compare ): static {
		return $this->set_string( 'compare', $compare->value );
	}

	/**
	 * @throws Invalid_Field
	 */
	public function to_instance(): WP_Date_Query {
		$instance = Object_Helper::make_class( [
				'class' => $this->instance,
				'args'  => $this->to_array(),
			]
		);

		if ( $instance instanceof WP_Date_Query ) {
			return $instance;
		} else {
			throw new Invalid_Field( message: 'Query Instance must be an instance of WP_Date_Query.', data: [ 'received' => $this->instance ] );
		}
	}

}