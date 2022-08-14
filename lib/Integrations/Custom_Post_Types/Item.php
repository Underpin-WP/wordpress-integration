<?php

namespace Underpin\WordPress\Integrations\Custom_Post_Types;

use Underpin\Factories\Log_Item;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Exceptions\WP_Error;
use Underpin\WordPress\Interfaces\Loader_Item;

abstract class Item implements Feature_Extension, Loader_Item {

	/**
	 * The post type args.
	 *
	 * @since 1.0.0
	 *
	 * @var array The list of post type args. See https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	protected array $args = [];

	/**
	 * Information on any arguments that do not have a description below can be found in the register_post_type docs.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/ for description of arguments
	 *
	 * @param string      $id The post type ID. Maps to register_post_type's "$post_type" argument.
	 * @param string|null $label
	 * @param array|null  $labels
	 * @param string|null $description
	 * @param bool|null   $public
	 * @param bool|null   $hierarchical
	 * @param bool|null   $exclude_from_search
	 * @param bool|null   $publicly_queryable
	 * @param bool|null   $show_ui
	 * @param bool|null   $show_in_menu
	 * @param bool|null   $show_in_nav_menus
	 * @param bool|null   $show_in_admin_bar
	 * @param bool|null   $show_in_rest
	 * @param string|null $rest_base
	 * @param string|null $rest_namespace
	 * @param string|null $rest_controller_class
	 * @param int|null    $menu_position
	 * @param string|null $menu_icon
	 * @param array|null  $capability_type
	 * @param string|null $capabilities
	 * @param bool|null   $has_meta_cap
	 * @param array|null  $supports
	 * @param array|null  $taxonomies
	 * @param bool|null   $has_archive
	 * @param bool|array| $has_rewrite
	 * @param string|null $query_var
	 * @param bool|null   $can_export
	 * @param bool|null   $delete_with_user
	 * @param array|null  $template
	 * @param bool|null   $template_lock
	 */
	public function __construct(
		public readonly string          $id,
		public readonly ?string         $label = null,
		public readonly ?array          $labels = null,
		public readonly ?string         $description = null,
		public readonly ?bool           $public = null,
		public readonly ?bool           $hierarchical = null,
		public readonly ?bool           $exclude_from_search = null,
		public readonly ?bool           $publicly_queryable = null,
		public readonly ?bool           $show_ui = null,
		public readonly ?bool           $show_in_menu = null,
		public readonly ?bool           $show_in_nav_menus = null,
		public readonly ?bool           $show_in_admin_bar = null,
		public readonly ?bool           $show_in_rest = null,
		public readonly ?string         $rest_base = null,
		public readonly ?string         $rest_namespace = null,
		public readonly ?string         $rest_controller_class = null,
		public readonly ?int            $menu_position = null,
		public readonly ?string         $menu_icon = null,
		public readonly ?array          $capability_type = null,
		public readonly ?string         $capabilities = null,
		public readonly ?bool           $has_meta_cap = null,
		public readonly ?array          $supports = null,
		public readonly ?array          $taxonomies = null,
		public readonly ?bool           $has_archive = null,
		public readonly null|bool|array $has_rewrite = null,
		public readonly ?string         $query_var = null,
		public readonly ?bool           $can_export = null,
		public readonly ?bool           $delete_with_user = null,
		public readonly ?array          $template = null,
		public readonly ?bool           $template_lock = null
	) {
	}

	public function register_meta_box(): void {
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
		$registered = register_post_type( $this->get_id(), Array_Helper::where_not_null( [
			'label'                 => $this->label,
			'labels'                => $this->labels,
			'description'           => $this->description,
			'public'                => $this->public,
			'hierarchical'          => $this->hierarchical,
			'exclude_from_search'   => $this->exclude_from_search,
			'publicly_queryable'    => $this->publicly_queryable,
			'show_ui'               => $this->show_ui,
			'show_in_menu'          => $this->show_in_menu,
			'show_in_nav_menus'     => $this->show_in_nav_menus,
			'show_in_admin_bar'     => $this->show_in_admin_bar,
			'show_in_rest'          => $this->show_in_rest,
			'rest_base'             => $this->rest_base,
			'rest_namespace'        => $this->rest_namespace,
			'rest_controller_class' => $this->rest_controller_class,
			'menu_position'         => $this->menu_position,
			'menu_icon'             => $this->menu_icon,
			'capability_type'       => $this->capability_type,
			'capabilities'          => $this->capabilities,
			'has_meta_cap'          => $this->has_meta_cap,
			'supports'              => $this->supports,
			'taxonomies'            => $this->taxonomies,
			'has_archive'           => $this->has_archive,
			'has_rewrite'           => $this->has_rewrite,
			'query_var'             => $this->query_var,
			'can_export'            => $this->can_export,
			'delete_with_user'      => $this->delete_with_user,
			'template'              => $this->template,
			'template_lock'         => $this->template_lock,
			'meta_box_cb'           => [ $this, 'register_meta_box' ],
		] ) );

		if ( is_wp_error( $registered ) ) {
			throw new WP_Error( $registered );
		} else {
			Logger::log(
				'notice',
				new Log_Item(
					code   : 'custom_post_type_registered',
					message: 'A custom post type has been registered.',
					data   : $this->args
				)
			);
		}
	}

	public function get_id(): string {
		return $this->id;
	}

}