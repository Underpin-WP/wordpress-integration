<?php

namespace Underpin_WordPress\Exceptions;


use Underpin\Exceptions\Operation_Failed;

class Post_Delete_Failed extends Operation_Failed {

	public function __construct( $id, ?string $type = 'error' ) {
		parent::__construct(
			message: 'A post failed to delete.',
			type   : $type,
			ref    : $id
		);
	}

}