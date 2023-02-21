<?php

namespace Underpin\WordPress\Integrations;

use Underpin\Exceptions\Cached_Item_Not_Found;
use Underpin\Interfaces\Cache_Strategy;

class Cache implements Cache_Strategy
{

	public function __construct(protected $expiration = 0)
	{

	}

	/**
	 * @inheritDoc
	 */
	public function get(string $key) : mixed
	{
		$result = get_transient($key);

		if (! $result) {
			throw new Cached_Item_Not_Found($key, 'Transient');
		}

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function set(string $key, mixed $value) : void
	{
		set_transient($key, $value, $this->expiration);
	}
}