<?php

namespace Underpin_WordPress\Factories;


use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Model_Item;
use Underpin\Interfaces\Query;
use WP_Query;

class Post_Type_Query implements Query {

	/**
	 * @param WP_Query                 $original
	 * @param class-string<Model_Item> $model_instance
	 */
	public function __construct( protected WP_Query $original, protected string $model_instance ) {

	}

	public function get_results(): array {
		return Array_Helper::hydrate( $this->original->get_posts(), $this->model_instance );
	}

	public function get_count(): int {
		return $this->original->post_count;
	}

}