<?php

namespace Underpin\WordPress\Exceptions;


use Underpin\Exceptions\Exception;

class Post_Not_Found extends Exception {

	public function __construct( int $id, $type = 'error' ) {
		parent::__construct(
			message: "The request post could not be found",
			type   : $type,
			ref    : $id
		);
	}

}