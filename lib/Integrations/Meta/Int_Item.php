<?php

namespace Underpin\WordPress\Integrations\Meta;


abstract class Int_Item extends Item {

	public function sanitize( mixed $meta_value, string $meta_key, string $object_type ): int {
		return (int) $meta_value;
	}

}