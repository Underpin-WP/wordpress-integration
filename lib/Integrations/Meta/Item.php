<?php

namespace Underpin\WordPress\Integrations\Meta;


use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\WordPress\Enums\Meta_Types;
use Underpin\WordPress\Interfaces\Loader_Item;
use UnitEnum;

abstract class Item implements Loader_Item, Feature_Extension {

	protected string $key;
	protected string $object_type;

	public function __construct(
		protected string           $id,
		protected mixed            $default_value,
		Meta_Types|UnitEnum|string $object_type,
		protected ?string          $description = null,
		protected ?bool            $single = null,
		protected ?bool            $show_in_rest = null,
		protected ?string          $subtype = null,
		?string                    $key = null,
	) {
		$this->object_type = is_string( $object_type ) ? $object_type : $object_type->name;
		if ( ! $key ) $this->key = $id;
	}

	abstract public function has_permission( bool $allowed, string $meta_key, int $object_id, int $user_id, string $cap, array $caps ): bool;

	abstract public function sanitize( mixed $meta_value, string $meta_key, string $object_type );

	public function get_subtype(): string {
		return $this->subtype;
	}

	public function get_description(): string {
		return $this->description;
	}

	public function get_single(): bool {
		return $this->single;
	}

	public function get_default_value(): string {
		return $this->default_value;
	}

	public function get_key(): string {
		return $this->key;
	}

	public function get_id(): string {
		return $this->id;
	}

	public function get_show_in_rest(): bool {
		return $this->show_in_rest;
	}

	public function get_object_type(): string {
		return $this->object_type;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions(): void {
		add_action( 'init', [ $this, 'register_meta' ] );
	}

	/**
	 * Registers the post meta
	 *
	 * @since 1.0.0
	 */
	public function register_meta() {
		register_meta( $this->get_object_type(), $this->get_key(), Array_Helper::where_not_null( [
			'object_subtype'    => $this->get_subtype(),
			'type'              => $this->get_type(),
			'description'       => $this->get_description(),
			'single'            => $this->get_single(),
			'default'           => $this->get_default_value(),
			'sanitize_callback' => [ $this, 'sanitize' ],
			'auth_callback'     => [ $this, 'has_permission' ],
			'show_in_rest'      => $this->get_show_in_rest(),
		] ) );
	}

}