<?php

namespace Underpin\WordPress\Builders;

use DateTime;
use DateTimeZone;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Model_Item;
use Underpin\WordPress\Abstracts\Model_Builder;
use Underpin\WordPress\Custom_Post_Types\Post_Statuses;
use Underpin\WordPress\Enums\Post_Types;
use Underpin\WordPress\Enums\WP_Post_Fields;
use UnitEnum;
use WP_Post;

class Post_Builder extends Model_Builder {

	protected array $args = [];

	/**
	 * @param class-string<Model_Item> $instance
	 */
	public function __construct( protected string $instance ) {
	}

	public function set_type( Post_Types|UnitEnum|string $type ): static {
		return $this->set_string( 'type', is_string( $type ) ? $type : $type->name );
	}

	public function set_id( int $id ): static {
		return $this->set_int( 'ID', $id );
	}

	public function set_author( int $id ): static {
		return $this->set_int( 'post_author', $id );
	}

	public function set_content( string $content ): static {
		return $this->set_string( 'post_content', $content );
	}

	public function set_filtered_content( string $content ): static {
		return $this->set_string( 'post_content_filtered', $content );
	}

	public function set_parent( int $id ): static {
		return $this->set_int( 'post_parent', $id );
	}

	public function set_guid( string $id ): static {
		return $this->set_string( 'guid', $id );
	}

	public function set_menu_order( int $order ): static {
		return $this->set_int( 'menu_order', $order );
	}

	public function set_mime_type( string $type ): static {
		return $this->set_string( 'post_mime_type', $type );
	}

	public function set_comment_count( int $count ): static {
		return $this->set_int( 'comment_count', $count );
	}

	public function set_excerpt( string $excerpt ): static {
		return $this->set_string( 'post_excerpt', $excerpt );
	}

	/**
	 * @param Post_Statuses|UnitEnum $status The enum case to set the status against.
	 *
	 * @return $this
	 * @see Post_Statuses - recommended enum to use to set status when working with core statuses.
	 */
	public function set_status( Post_Statuses|UnitEnum $status ): static {
		return $this->set_string( 'post_status', $status->name );
	}

	/**
	 * @param bool $enabled True if enabled, otherwise false.
	 *
	 * @return $this
	 */
	public function set_comment_status( bool $enabled ): static {
		return $this->set_string( 'comment_status', $enabled ? 'open' : 'closed' );
	}

	/**
	 * @param bool $enabled True if enabled, otherwise false.
	 *
	 * @return $this
	 */
	public function set_ping_status( bool $enabled ): static {
		return $this->set_string( 'ping_status', $enabled ? 'open' : 'closed' );
	}

	public function set_post_password( string $password ): static {
		return $this->set_string( 'post_password', $password );
	}

	public function set_post_slug( string $slug ): static {
		return $this->set_string( 'post_name', $slug );
	}

	public function to_ping( string ...$urls ): static {
		return $this->set_string( 'to_ping', implode( ' ', func_get_args() ) );
	}

	public function pinged( string ...$urls ): static {
		return $this->set_string( 'pinged', implode( ' ', func_get_args() ) );
	}

	public function set_modified_date( DateTime $date ): static {
		$date->setTimezone( new DateTimeZone( 'UTC' ) );
		$this->args['post_modified_gmt'] = $date->format( 'Y-m-d H:i:s' );
		$date->setTimezone( wp_timezone() );
		$this->args['post_modified'] = $date->format( 'Y-m-d H:i:s' );

		return $this;
	}

	public function set_publish_date( DateTime $date ): static {
		$date->setTimezone( new DateTimeZone( 'UTC' ) );
		$this->args['post_date_gmt'] = $date->format( 'Y-m-d H:i:s' );
		$date->setTimezone( wp_timezone() );
		$this->args['post_date'] = $date->format( 'Y-m-d H:i:s' );

		return $this;
	}

	public function set_title( string $title ): static {
		return $this->set_string( 'post_title', $title );
	}

	/**
	 * Clones a WP post object's arguments into this builder.
	 *
	 * @param WP_Post        $post
	 * @param WP_Post_Fields ...$fields Specify fields to clone. If left blank, all fields will be cloned, execpt for the
	 *                                  ID.
	 *
	 * @return $this
	 */
	public function clone( \WP_Post $post, WP_Post_Fields ...$fields ): static {
		$args = $post->to_array();

		// Only set the specified fields.
		if ( empty( $fields ) ) {
			unset( $args['ID'] );
		} else {
			$args = Array_Helper::intersect_keys( $args, Array_Helper::map( $fields, fn ( WP_Post_Fields $field ) => $field->value ) );
		}

		$this->args = Array_Helper::merge( $this->args, $args );

		return $this;
	}

	public function to_instance(): Model_Item {
		return new $this->instance( new WP_Post( (object) $this->to_array() ) );
	}

	public function to_array(): array {
		return $this->args;
	}

}