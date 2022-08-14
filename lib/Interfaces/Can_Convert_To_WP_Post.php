<?php

namespace Underpin_WordPress\Interfaces;

use WP_Post;

interface Can_Convert_To_WP_Post {
	function to_wp_post(): WP_Post;
}
