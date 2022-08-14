<?php

namespace Underpin_WordPress\Builders;

use Underpin_WordPress\Abstracts\Builder;
use Underpin_WordPress\Enums\Compare;
use Underpin_WordPress\Enums\Database_Operator;
use Underpin_WordPress\Enums\Database_Type;

class Meta_Query_Parameter extends Builder {

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

}