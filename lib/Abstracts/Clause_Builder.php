<?php

namespace Underpin\WordPress\Abstracts;


use Underpin\Abstracts\Builder;
use Underpin\Interfaces\Can_Convert_To_Instance;
use Underpin\WordPress\Enums\Relationships;

abstract class Clause_Builder extends Builder implements Can_Convert_To_Instance {

	protected array $clauses = [];

	public function next_clause(): static {
		$this->clauses[] = $this->args;
		$this->args      = [];


		return $this;
	}

	public function to_array(): array {
		if ( ! empty( $this->args ) ) {
			$this->next_clause();
		}

		return $this->clauses;
	}

	public function set_relation( Relationships $relation ): static {
		return $this->set_string( 'relation', $relation->value );
	}

}