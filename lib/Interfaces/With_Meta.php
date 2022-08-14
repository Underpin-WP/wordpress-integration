<?php

namespace Underpin_WordPress\Interfaces;


use Underpin_WordPress\Meta\Loader;

interface With_Meta {

	public function meta(): Loader;

}