<?php

namespace Underpin\WordPress\Builders;

use Underpin\Interfaces\Can_Convert_To_Instance;
use Underpin\WordPress\Abstracts\Clause_Builder;
use Underpin\WordPress\Enums\Compare;
use Underpin\WordPress\Enums\Database_Operator;
use Underpin\WordPress\Enums\Database_Type;
use WP_Meta_Query;

class Meta_Query_Parameter extends Clause_Builder implements Can_Convert_To_Instance {

	public function set_keys( string ...$keys ): static {
		return $this->set_varidic( 'key', 'key', $keys );
	}

	public function set_compare_key( Database_Operator|Compare $key ): static {
		return $this->set_string( 'compare_key', $key->value );
	}

	public function set_type_key( string $key ): static {
		return $this->set_string( 'type_key', $key );
	}

	public function set_value( string ...$values ): static {
		return $this->set_varidic( 'value', 'value', $values );
	}

	public function set_compare( Database_Operator|Compare $compare ): static {
		return $this->set_string( 'compare', $compare->value );
	}

	public function set_type( Database_Type $type ): static {
		return $this->set_string( 'type', $type->value );
	}

	public function to_array(): array {
		return $this->args;
	}

	public function to_instance(): WP_Meta_Query {
		return new WP_Meta_Query($this->to_array());
	}
}