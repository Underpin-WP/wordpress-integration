<?php

namespace Underpin\WordPress\Integrations\Taxonomies;

use Underpin\Factories\Log_Item;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Registries\Logger;
use Underpin\WordPress\Exceptions\WP_Error;
use Underpin\Interfaces\Loader_Item;

class Item implements Feature_Extension, Loader_Item {

	/**
	 * The post type args.
	 *
	 * @since 1.0.0
	 *
	 * @var array The list of post type args. See https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */
	protected array $assoc_args = [];

	/**
	 * Information on any arguments that do not have a description below can be found in the register_taxonomy docs.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/ for description of arguments
	 *
	 * @param string $id The post type ID. Maps to register_taxonomy's "$taxonomy" argument.
	 */
	public function __construct(
		public readonly string           $id,
		public readonly array            $object_types,
		public readonly ?array           $labels,
		public readonly ?string          $description,
		public readonly ?bool            $public,
		public readonly ?bool            $publicly_queryable,
		public readonly ?bool            $hierarchical,
		public readonly ?bool            $show_ui,
		public readonly ?bool            $show_in_menu,
		public readonly ?bool            $show_in_nav_menus,
		public readonly ?bool            $show_in_rest,
		public readonly ?bool            $show_tagcloud,
		public readonly ?bool            $show_in_quick_edit,
		public readonly ?bool            $sort,
		public readonly ?bool            $show_admin_column,
		public readonly ?array           $capabilities,
		public readonly null|bool|array  $rewrite,
		public readonly null|string|bool $query_var,
		public readonly ?string          $rest_base,
		public readonly ?string          $rest_namespace,
		public readonly ?string          $rest_controller_class,
		public readonly ?array           $default_term,
		public readonly ?array           $args
	) {
	}

	public function register_meta_box( $post, $box ): void {
		if ( $this->hierarchical === true ) {
			post_categories_meta_box( $post, $box );
		} else {
			post_tags_meta_box( $post, $box );
		}
	}

	public function update_count( $terms, $taxonomy ): void {
		_update_generic_term_count( $terms, $taxonomy );
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions(): void {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.0.0
	 * @throws WP_Error
	 */
	public function register() {
		$registered = register_taxonomy( $this->get_id(), $this->object_types, Array_Helper::where_not_null( [
			'labels'                => $this->labels,
			'description'           => $this->description,
			'public'                => $this->public,
			'publicly_queryable'    => $this->publicly_queryable,
			'hierarchical'          => $this->hierarchical,
			'show_ui'               => $this->show_ui,
			'show_in_menu'          => $this->show_in_menu,
			'show_in_nav_menus'     => $this->show_in_nav_menus,
			'show_in_rest'          => $this->show_in_rest,
			'show_tagcloud'         => $this->show_tagcloud,
			'show_in_quick_edit'    => $this->show_in_quick_edit,
			'sort'                  => $this->sort,
			'show_admin_column'     => $this->show_admin_column,
			'capabilities'          => $this->capabilities,
			'rewrite'               => $this->rewrite,
			'query_var'             => $this->query_var,
			'rest_base'             => $this->rest_base,
			'rest_namespace'        => $this->rest_namespace,
			'rest_controller_class' => $this->rest_controller_class,
			'default_term'          => $this->default_term,
			'args'                  => $this->args,
			'meta_box_cb'           => [ $this, 'register_meta_box' ],
			'update_count_callback' => [ $this, 'update_count' ],
		] ) );

		if ( is_wp_error( $registered ) ) {
			throw new WP_Error( $registered );
		} else {
			Logger::log(
				'notice',
				new Log_Item(
					code   : 'custom_post_type_registered',
					message: 'A custom post type has been registered.',
					data   : $this->assoc_args
				)
			);
		}
	}

	public function get_id(): string {
		return $this->id;
	}

}