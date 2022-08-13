<?php

namespace Underpin\WordPress\Abstracts;

use Underpin\Interfaces\Model;
use Underpin\WordPress\Interfaces\Can_Convert_To_WP_Post;

abstract class Model_To_WP_Post_Adapter implements Can_Convert_To_WP_Post {

	public function __construct( protected Model $model ) {

	}

}