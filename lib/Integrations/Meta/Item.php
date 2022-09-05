<?php

namespace Underpin\WordPress\Integrations\Meta;


use Closure;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\WordPress\Enums\Meta_Types;
use Underpin\Enums\Types;
use Underpin\Interfaces\Loader_Item;
use UnitEnum;

abstract class Item implements Loader_Item, Feature_Extension {

	public readonly string $key;
	public readonly string $object_type;

	/**
	 * @param string                     $id
	 * @param mixed                      $default_value
	 * @param Meta_Types|UnitEnum|string $object_type
	 * @param Types                      $type
	 * @param string|null                $description
	 * @param bool|null                  $single
	 * @param bool|null                  $show_in_rest
	 * @param string|null                $subtype
	 * @param Closure|null               $sanitize_callback
	 * @param string|null                $key
	 */
	public function __construct(
		public readonly string           $id,
		public readonly mixed            $default_value,
		Meta_Types|UnitEnum|string $object_type,
		public readonly Types            $type,
		public readonly ?string          $description = null,
		public readonly ?bool            $single = null,
		public readonly ?bool            $show_in_rest = null,
		public readonly ?string          $subtype = null,
		public readonly ?Closure         $sanitize_callback = null,
		?string                    $key = null,
	) {
		$this->object_type = is_string( $object_type ) ? $object_type : $object_type->name;
		if ( ! $key ) $this->key = $id;
	}

	abstract public function has_permission( bool $allowed, string $meta_key, int $object_id, int $user_id, string $cap, array $caps ): bool;

	public function get_id(): string {
		return $this->id;
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
		register_meta( $this->object_type, $this->key, Array_Helper::where_not_null( [
			'object_subtype'    => $this->subtype,
			'type'              => $this->type,
			'description'       => $this->description,
			'single'            => $this->single,
			'default'           => $this->default_value,
			'sanitize_callback' => is_callable( $this->sanitize_callback ) ? [ $this, 'sanitize_callback' ] : null,
			'auth_callback'     => [ $this, 'has_permission' ],
			'show_in_rest'      => $this->show_in_rest,
		] ) );
	}

}