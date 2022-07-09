<?php

namespace Underpin\WordPress\Meta;


use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\WordPress\Enums\Meta_Types;
use Underpin\WordPress\Enums\Types;
use Underpin\WordPress\Interfaces\Loader_Item;
use UnitEnum;
//TODO: YOU WERE HERE. YOU WERE ADDING ALL OF THE LOADERS. THIS REQUIRES A LOADER ITEM, A LOADER, AND A WITH_* INTERFACE.
abstract class Item implements Loader_Item, Feature_Extension {

	protected string $key;
	protected string $object_type;

	public function __construct(
		protected string           $id,
		protected string           $default_value,
		Meta_Types|UnitEnum|string $object_type,
		protected Types            $type,
		protected ?string          $description = null,
		protected ?bool            $single = null,
		protected ?bool            $show_in_rest = null,
		protected ?string          $subtype = null,
		?string                    $key = null,
	) {
		$this->object_type = is_string( $object_type ) ? $object_type : $object_type->name;
		if ( ! $key ) $this->key = $id;
	}

	public function has_permission( bool $allowed, string $meta_key, int $object_id, int $user_id, string $cap, array $caps ): bool {
		return $allowed;
	}

	abstract public function sanitize( string $meta_value, string $meta_key, string $object_type );

	public function get_subtype(): string {
		return $this->subtype;
	}

	public function get_description(): string {
		return $this->description;
	}

	public function get_single(): bool {
		return $this->single;
	}

	public function get_type(): string {
		return $this->type->name;
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
	 * Adds the metadata.
	 *
	 * @since 1.1.1
	 *
	 * @param int  $object_id
	 * @param bool $unique
	 *
	 * @return bool
	 */
	public function add( int $object_id, bool $unique = false ): bool {
		return add_metadata( $this->get_object_type(), $object_id, $this->get_key(), $this->get_default_value(), $unique );
	}

	/**
	 * Retrieves the record.
	 *
	 * @since 1.1.1
	 *
	 * @param int  $object_id   ID of the object metadata is for.
	 * @param bool $single      Optional. If true, return only the first value of the specified meta_key.
	 *                          This parameter has no effect if meta_key is not specified. Default false.
	 *
	 * @return mixed|void
	 */
	public function get( int $object_id, bool $single = false ) {
		return get_metadata( $this->get_object_type(), $object_id, $this->get_key(), $single );
	}

	/**
	 * Updates the record to the specified value.
	 *
	 * @since 1.1.1
	 *
	 * @param int          $object_id  ID of the object metadata is for.
	 * @param mixed        $value      Metadata value. Must be serializable if non-scalar.
	 * @param mixed|string $prev_value Optional. Previous value to check before updating.
	 *                           If specified, only update existing metadata entries with
	 *                           this value. Otherwise, update all entries. Default empty.
	 *
	 * @return bool True if updated, otherwise false
	 */
	public function update( int $object_id, mixed $value, mixed $prev_value = '' ): bool {
		return update_metadata( $this->get_object_type(), $object_id, $this->get_key(), $value, $prev_value );
	}

	/**
	 * Deletes the record.
	 *
	 * @since 1.1.1
	 *
	 * @param int          $object_id ID of the object metadata is for.
	 * @param mixed|string $value     Optional. Metadata value. Must be serializable if non-scalar.
	 *                           If specified, only delete metadata entries with this value.
	 *                           Otherwise, delete all entries with the specified meta_key.
	 *                           Pass `null`, `false`, or an empty string to skip this check.
	 *                           (For backward compatibility, it is not possible to pass an empty string
	 *                           to delete those entries with an empty string for a value.)
	 *
	 * @return bool
	 */
	public function delete( int $object_id, mixed $value = '' ): bool {
		return delete_metadata( $this->get_object_type(), $object_id, $this->get_key(), $value );
	}

	/**
	 * Resets the record to the default value.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id ID of the object metadata is for.
	 *
	 * @return bool
	 */
	public function reset( int $object_id ): bool {
		return $this->update( $object_id, $this->get_default_value() );
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