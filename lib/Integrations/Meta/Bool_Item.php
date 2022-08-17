<?php

namespace Underpin\WordPress\Integrations\Meta;


abstract class Bool_Item extends Item {

	public function sanitize( mixed $meta_value, string $meta_key, string $object_type ): bool {
		return (bool) $meta_value;
	}

}