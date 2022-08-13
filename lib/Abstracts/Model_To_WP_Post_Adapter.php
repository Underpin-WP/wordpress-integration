<?php

namespace Underpin\WordPress\Abstracts;

use Underpin\Interfaces\Model;
use Underpin\WordPress\Interfaces\Can_Convert_To_WP_Post;
use WP_Post;

class Model_To_WP_Post_Adapter implements Can_Convert_To_WP_Post {

	public function __construct( protected Model $model ) {

	}

	function to_wp_post(): WP_Post {

	}

}