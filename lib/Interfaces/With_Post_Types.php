<?php

namespace Underpin_WordPress\Interfaces;


use Underpin_WordPress\Meta\Loader;

interface With_Post_Types {

	public function custom_post_types(): Loader;

}