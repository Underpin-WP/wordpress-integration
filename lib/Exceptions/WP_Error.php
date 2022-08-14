<?php

namespace Underpin_WordPress\Exceptions;


use Underpin\Exceptions\Exception;
use \WP_Error as WP_Error_Core;

class WP_Error extends Exception {

	public function __construct( WP_Error_Core $error, $type = 'error', $previous = null ) {
		parent::__construct(
			message : $error->get_error_message(),
			code    : $error->get_error_code(),
			type    : $type,
			previous: $previous,
			data    : $error->get_error_data() );
	}

}