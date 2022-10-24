<?php

namespace Underpin\WordPress\Exceptions;


use Underpin\Exceptions\Operation_Failed;

class Term_Delete_Failed extends Operation_Failed {
	public function __construct( $id, ?string $type = 'error' ) {
		parent::__construct(
			message: 'A term failed to delete.',
			type   : $type,
			ref    : $id
		);
	}
}