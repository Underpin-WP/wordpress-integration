<?php

namespace Underpin_WordPress\Exceptions;


use Underpin\Exceptions\Exception;

class Invalid_Post_Type extends Exception {

	public function __construct( int $id, string $expected_type, ?string $type = 'error' ) {
		parent::__construct(
			message: "The request post is not a $expected_type type",
			type   : $type,
			ref    : $id
		);
	}

}