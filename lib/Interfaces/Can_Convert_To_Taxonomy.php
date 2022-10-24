<?php

namespace Underpin\WordPress\Interfaces;

use WP_Taxonomy;

interface Can_Convert_To_Taxonomy {
	function to_taxonomy(): WP_Taxonomy;
}
