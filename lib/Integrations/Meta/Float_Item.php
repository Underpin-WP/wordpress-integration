<?php

namespace Underpin\WordPress\Integrations\Meta;


abstract class Float_Item extends Item {

	public function sanitize( mixed $meta_value, string $meta_key, string $object_type ): float {
		return (float) $meta_value;
	}

}