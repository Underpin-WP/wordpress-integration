<?php

namespace Underpin\WordPress\Integrations\Meta;


use Underpin\Interfaces\Identifiable;
use WP_User;

class WP_User_Identity implements Identifiable {

	public function __construct( protected WP_User $original ) {
	}

	public function get_id(): string|int {
		return $this->original->ID;
	}

}