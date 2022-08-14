<?php

namespace Underpin_WordPress\Exceptions;


use Underpin\Exceptions\Operation_Failed;

class Post_Delete_Canceled extends Operation_Failed {

	public function __construct( $id, ?string $type = 'warning', array $data = [] ) {
		parent::__construct(
			message: 'A post deletion was cancelled early.',
			type   : $type,
			ref    : $id,
			data   : $data
		);
	}

}