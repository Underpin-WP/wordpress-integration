<?php

namespace Underpin\WordPress\Integrations\Rest_Fields;


use Closure;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Interfaces\Loader_Item;

class Item implements Loader_Item, Feature_Extension {

	public function __construct(
		protected string $id,
		public readonly string       $object_type,
		public readonly string       $name,
		public readonly Closure|null $get_callback = null,
		public readonly Closure|null $update_callback = null,
		public readonly ?array       $schema = null
	) {
	}

	public function get_id(): string {
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions(): void {
		add_action( 'init', [ $this, 'register_rest_field' ] );
	}

	/**
	 * Registers the post meta
	 *
	 * @since 1.0.0
	 */
	public function register_rest_field() {
		register_rest_field( $this->object_type, $this->name, Array_Helper::where_not_null( [
			'get_callback'    => $this->get_callback,
			'update_callback' => $this->update_callback,
			'schema'          => $this->schema,
		] ) );
	}

}