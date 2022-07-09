<?php

namespace Underpin\WordPress\Interfaces;


use Underpin\WordPress\Meta\Loader;

interface With_Meta {

	public function meta(): Loader;

}