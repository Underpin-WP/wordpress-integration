<?php

namespace Underpin\WordPress\Builders;


use Underpin\WordPress\Abstracts\Builder;
use Underpin\WordPress\Enums\Database_Operator;
use Underpin\WordPress\Enums\Tax_Query_fields;

class Tax_Query_Parameter extends Builder {

	public function set_taxonomy( string $taxonomy ): static {
		return $this->set_string( 'taxonomy', $taxonomy );
	}

	public function set_terms( int ...$terms ): static {
		return $this->set_varidic( 'terms', 'terms', $terms );
	}

	public function set_field( Tax_Query_fields $field ): static {
		return $this->set_string( 'field', $field->name );
	}

	public function set_operator( Database_Operator $operator ): static {
		return $this->set_string( 'operator', $operator->value );
	}

	public function set_include_children( bool $include_children ): static {
		return $this->set_bool( 'include_children', $include_children );
	}

}