<?php

namespace Underpin\WordPress\Builders;

use Underpin\Exceptions\Invalid_Field;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\Object_Helper;
use Underpin\WordPress\Abstracts\Clause_Builder;
use WP_Meta_Query;

class Meta_Query_Builder extends Clause_Builder {

	protected array $clauses = [];

	public function __construct( protected string $instance = WP_Meta_Query::class ) {
	}

	public function set_parameters( Meta_Query_Parameter ...$parameters ): static {
		$parameters = Array_Helper::map( $parameters, fn ( Meta_Query_Parameter $parameter ) => $parameter->to_array() );

		$this->args = Array_Helper::merge( $this->args ?? [], $parameters );

		return $this;
	}

}