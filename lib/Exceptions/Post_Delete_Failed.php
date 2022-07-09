<?php

namespace Underpin\WordPress\Exceptions;


use Underpin\Exceptions\Exception;

class Post_Delete_Failed extends Exception {

	public function __construct( $id, ?string $type = 'error' ) {
		parent::__construct(
			message: 'A post failed to delete.',
			type   : $type,
			ref    : $id
		);
	}

}