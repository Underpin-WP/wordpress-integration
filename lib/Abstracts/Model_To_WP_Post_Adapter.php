<?php

namespace Underpin\WordPress\Abstracts;

use Underpin\Interfaces\Can_Convert_To_Array;
use Underpin\Interfaces\Model;
use Underpin\WordPress\Interfaces\Can_Convert_To_WP_Post;

abstract class Model_To_WP_Post_Adapter implements Can_Convert_To_WP_Post, Can_Convert_To_Array {

	public function __construct( protected Model $model ) {

	}

}