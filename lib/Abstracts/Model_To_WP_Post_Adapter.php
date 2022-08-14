<?php

namespace Underpin_WordPress\Abstracts;

use Underpin\Interfaces\Model;
use Underpin_WordPress\Interfaces\Can_Convert_To_WP_Post;

abstract class Model_To_WP_Post_Adapter implements Can_Convert_To_WP_Post {

	public function __construct( protected Model $model ) {

	}

}