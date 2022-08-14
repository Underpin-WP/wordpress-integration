<?php

namespace Underpin\WordPress\Interfaces;


use Underpin\WordPress\Meta\Loader;

interface With_Post_Types {

	public function custom_post_types(): Loader;

}