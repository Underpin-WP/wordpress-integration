<?php

namespace Underpin\WordPress\Builders;

use Underpin\Abstracts\Query_Builder;
use Underpin\Helpers\Array_Helper;
use Underpin\WordPress\Custom_Post_Types\Post_Statuses;
use Underpin\WordPress\Enums\Capabilities;
use Underpin\WordPress\Enums\Compare;
use Underpin\WordPress\Enums\Order;
use Underpin\WordPress\Enums\Order_By;
use Underpin\WordPress\Enums\Post_Types;
use Underpin\WordPress\Enums\Query_Return_Fields;
use Underpin\WordPress\Enums\Roles;
use UnitEnum;
use WP_Query;

class Post_Query_Builder extends Query_Builder {

	protected array $args = [];

	/**
	 * @param class-string<WP_Query> $instance
	 */
	public function __construct( protected string $instance = WP_Query::class ) {
	}

	public function set_attachment_id( int $id ): static {
		return $this->set_int( 'attachment_id', $id );
	}

	public function set_post_mime_type( string $type ): static {
		return $this->set_string( 'post_mime_type', $type );
	}

	public function set_author_name( string $name ): static {
		return $this->set_string( 'author_name', $name );
	}

	public function set_slugs( string ...$slugs ): static {
		return $this->set_varidic( 'name', 'name__in', $slugs );
	}

	public function set_pagination( bool $should_paginate ): static {
		return $this->set_bool( 'nopaging', $should_paginate );
	}

	public function set_no_found_rows( bool $should_not_find_rows ): static {
		return $this->set_bool( 'no_found_rows', $should_not_find_rows );
	}

	public function set_authors( int ...$ids ): static {
		return $this->set_varidic( 'author', 'author__in', $ids );
	}

	public function set_offset( int $offset ): static {
		return $this->set_int( 'offset', $offset );
	}

	public function set_order( Order $order, Order_By ...$order_by ): static {
		return $this
			->set_string( 'order', $order->value )
			->set_array( 'orderby', Array_Helper::map( $order_by, fn ( Order_By $order_by ) => $order_by->value ) );
	}

	public function set_page( int $page ): static {
		return $this->set_int( 'paged', $page );
	}

	public function set_permission( Roles|Capabilities|UnitEnum|string $permission ): static {
		return $this->set_string( 'perm', is_string( $permission ) ? $permission : $permission->name );
	}

	/**
	 * @param bool $enabled True if enabled, otherwise false.
	 *
	 * @return $this
	 */
	public function set_ping_status( bool $enabled ): static {
		return $this->set_string( 'ping_status', $enabled ? 'open' : 'closed' );
	}

	public function set_category( int $id ): static {
		return $this->set_int( 'cat', $id );
	}

	public function set_author_not_in( int ...$ids ): static {
		return $this->set_array( 'author__not_in', $ids );
	}

	public function set_category_in( int ...$ids ): static {
		return $this->set_array( 'category__in', $ids );
	}

	public function set_category_not_in( int ...$ids ): static {
		return $this->set_array( 'category__not_in', $ids );
	}

	public function set_category_and( int ...$ids ): static {
		return $this->set_array( 'category__and', $ids );
	}

	public function cache_results( bool $set ): static {
		return $this->set_bool( 'cache_results', $set );
	}

	public function set_posts( int ...$ids ): static {
		return $this->set_varidic( 'p', 'post__in', $ids );
	}

	public function set_post_not_in( int ...$ids ): static {
		return $this->set_array( 'post__not_in', $ids );
	}

	public function set_post_names( string ...$slugs ): static {
		return $this->set_array( 'post_name__in', $slugs );
	}

	public function set_post_parents( int ...$parent_ids ): static {
		return $this->set_varidic( 'post_parent', 'post_parent__in', $parent_ids );
	}

	public function set_post_parent_not_in( int ...$parent_ids ): static {
		return $this->set_array( 'post_parent__not_in', $parent_ids );
	}

	public function set_post_types( Post_Types|UnitEnum ...$post_types ): static {
		return $this->set_array( 'post_type', Array_Helper::map( $post_types, fn ( UnitEnum $type ) => $type->name ) );
	}

	public function set_post_status( Post_Statuses|UnitEnum ...$statuses ): static {
		return $this->set_array( 'post_status', Array_Helper::map( $statuses, fn ( UnitEnum $type ) => $type->name ) );
	}

	public function set_posts_per_page( int $per_page ): static {
		return $this->set_int( 'posts_per_page', $per_page );
	}

	public function set_posts_per_archive_page( int $per_page ): static {
		return $this->set_int( 'posts_per_archive_page', $per_page );
	}

	public function set_search( string $search, bool $by_phrase = false ): static {
		return $this->set_string( 's', $search )->set_bool( 'sentence', $by_phrase );
	}

	public function set_tag_slug( string $slug ): static {
		return $this->set_string( 'tag', $slug );
	}

	public function set_tag( int $id ): static {
		return $this->set_int( 'tag_id', $id );
	}

	public function set_tag_in( int ...$ids ): static {
		return $this->set_array( 'tag__in', $ids );
	}

	public function set_tag_and( int ...$ids ): static {
		return $this->set_array( 'tag__and', $ids );

	}

	public function set_tag_slug_in( string ...$slugs ): static {
		return $this->set_array( 'tag_slug__in', $slugs );
	}

	public function set_tag_slug_and( string ...$slugs ): static {
		return $this->set_array( 'tag_slug__and', $slugs );
	}

	public function set_title( string $title ): static {
		return $this->set_string( 'title', $title );
	}

	public function set_update_post_meta_cache( bool $set ): static {
		return $this->set_bool( 'update_post_meta_cache', $set );
	}

	public function set_update_post_term_cache( bool $set ): static {
		return $this->set_bool( 'update_post_term_cache', $set );
	}

	public function set_lazy_load_term_meta( bool $set ): static {
		return $this->set_bool( 'lazy_load_term_meta', $set );
	}

	public function set_suppress_filters( bool $suppress ): static {
		return $this->set_bool( 'suppress_filters', $suppress );
	}

	public function set_type( Post_Types|UnitEnum|string ...$types ): static {
		return $this->set_array( 'type', Array_Helper::map( $types, fn ( $item ) => is_string( $item ) ? $item : $item->name ) );
	}

	public function set_category_name( string $slug ): static {
		return $this->set_string( 'category_name', $slug );
	}

	/**
	 * @param int          $count
	 * @param Compare|null $compare
	 *
	 * @return $this
	 */
	public function set_comment_count( int $count, ?Compare $compare = null ): static {
		if ( ! $compare ) {
			$this->args['comment_count'] = $count;
		} else {
			$this->args['comment_count'] = [ 'value' => $count, 'compare' => $compare->value ];
		}

		return $this;
	}

	public function set_comment_status( bool $open ): static {
		return $this->set_string( 'comment_status', $open ? 'open' : 'closed' );
	}

	public function set_comments_per_page( int $per_page ): static {
		return $this->set_int( 'comments_per_page', $per_page );
	}

	public function set_date_query( Date_Query_Builder $builder ): static {
		return $this->set_array( 'date_query', $builder->to_array() );
	}

	public function set_meta_query( Meta_Query_Builder $builder ): static {
		return $this->set_array( 'meta_query', $builder->to_array() );
	}

	public function set_menu_order( int $order ): static {
		return $this->set_int( 'menu_order', $order );
	}

	public function set_ignore_sticky( bool $ignore ): static {
		return $this->set_bool( 'ignore_sticky_posts', $ignore );
	}

	public function set_exact( bool $exact ): static {
		return $this->set_bool( 'exact', $exact );
	}

	public function set_fields( Query_Return_Fields|\BackedEnum $fields ): static {
		return $this->set_string( 'fields', $fields->value );
	}

	public function to_instance(): WP_Query {
		return new $this->instance( ...$this->to_array() );
	}

	public function to_array(): array {
		return $this->args;
	}

}