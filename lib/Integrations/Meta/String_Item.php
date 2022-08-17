<?php

namespace Underpin\WordPress\Integrations\Meta;


abstract class String_Item extends Item {

	public function sanitize( mixed $meta_value, string $meta_key, string $object_type ): string {
		return (string) $meta_value;
	}

}