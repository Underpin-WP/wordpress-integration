<?php

namespace Underpin\WordPress\Exceptions;


use Underpin\Exceptions\Exception;

class Post_Delete_Canceled extends Exception {

	public function __construct( $id, ?string $type = 'warning', array $data = [] ) {
		parent::__construct(
			message: 'A post deletion was cancelled early.',
			type   : $type,
			ref    : $id,
			data   : $data
		);
	}

}