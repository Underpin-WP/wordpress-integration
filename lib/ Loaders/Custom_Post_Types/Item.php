<?php

namespace Underpin\WordPress\Loaders\Custom_Post_Types;

use Underpin\Factories\Log_Item;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Interfaces\Identifiable;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Exceptions\Invalid_Post_Type;
use Underpin\WordPress\Exceptions\Post_Delete_Canceled;
use Underpin\WordPress\Exceptions\Post_Delete_Failed;
use Underpin\WordPress\Exceptions\Post_Not_Found;
use Underpin\WordPress\Exceptions\WP_Error;
use Underpin\WordPress\Builders\Post_Builder;
use Underpin\WordPress\Builders\Post_Query_Builder;
use Underpin\WordPress\Interfaces\Loader_Item;
use WP_Post;
use WP_Query;
use WP_REST_Request;

abstract class Item implements Identifiable, Feature_Extension, Loader_Item {

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
	 * @param string          $id                   The post type ID. Maps to register_post_type's "$post_type" argument.
	 * @param string          $query_instance       The WP_Query instance to provide when querying this post type.
	 * @param string          $post_object_instance The WP_Post instance to provide when building a post of this type.
	 * @param string|null     $label
	 * @param array|null      $labels
	 * @param string|null     $description
	 * @param bool|null       $public
	 * @param bool|null       $hierarchical
	 * @param bool|null       $exclude_from_search
	 * @param bool|null       $publicly_queryable
	 * @param bool|null       $show_ui
	 * @param bool|null       $show_in_menu
	 * @param bool|null       $show_in_nav_menus
	 * @param bool|null       $show_in_admin_bar
	 * @param bool|null       $show_in_rest
	 * @param string|null     $rest_base
	 * @param string|null     $rest_namespace
	 * @param string|null     $rest_controller_class
	 * @param int|null        $menu_position
	 * @param string|null     $menu_icon
	 * @param array|null      $capability_type
	 * @param string|null     $capabilities
	 * @param bool|null       $has_meta_cap
	 * @param array|null      $supports
	 * @param array|null      $taxonomies
	 * @param bool|null       $has_archive
	 * @param bool|array|null $has_rewrite
	 * @param string|null     $query_var
	 * @param bool|null       $can_export
	 * @param bool|null       $delete_with_user
	 * @param array|null      $template
	 * @param bool|null       $template_lock
	 */
	public function __construct(
		protected string          $id,
		protected string          $query_instance = WP_Query::class,
		protected string          $post_object_instance = WP_Post::class,
		protected ?string         $label = null,
		protected ?array          $labels = null,
		protected ?string         $description = null,
		protected ?bool           $public = null,
		protected ?bool           $hierarchical = null,
		protected ?bool           $exclude_from_search = null,
		protected ?bool           $publicly_queryable = null,
		protected ?bool           $show_ui = null,
		protected ?bool           $show_in_menu = null,
		protected ?bool           $show_in_nav_menus = null,
		protected ?bool           $show_in_admin_bar = null,
		protected ?bool           $show_in_rest = null,
		protected ?string         $rest_base = null,
		protected ?string         $rest_namespace = null,
		protected ?string         $rest_controller_class = null,
		protected ?int            $menu_position = null,
		protected ?string         $menu_icon = null,
		protected ?array          $capability_type = null,
		protected ?string         $capabilities = null,
		protected ?bool           $has_meta_cap = null,
		protected ?array          $supports = null,
		protected ?array          $taxonomies = null,
		protected ?bool           $has_archive = null,
		protected null|bool|array $has_rewrite = null,
		protected ?string         $query_var = null,
		protected ?bool           $can_export = null,
		protected ?bool           $delete_with_user = null,
		protected ?array          $template = null,
		protected ?bool           $template_lock = null
	) {
	}

	public function register_meta_box(): void {
	}

	/**
	 * @return string|null Retrieves the label argument for this post type.
	 */
	public function get_label(): ?string {
		return $this->label;
	}

	/**
	 * @return array|null Retrieves the labels argument for this post type.
	 */
	public function get_labels(): ?array {
		return $this->labels;
	}

	/**
	 * @return string|null Retrieves the description argument for this post type.
	 */
	public function get_description(): ?string {
		return $this->description;
	}

	/**
	 * @return bool|null Retrieves the public argument for this post type.
	 */
	public function get_public(): ?bool {
		return $this->public;
	}

	/**
	 * @return bool|null Retrieves the hierarchical argument for this post type.
	 */
	public function get_hierarchical(): ?bool {
		return $this->hierarchical;
	}

	/**
	 * @return bool|null Retrieves the exclude_from_search argument for this post type.
	 */
	public function get_exclude_from_search(): ?bool {
		return $this->exclude_from_search;
	}

	/**
	 * @return bool|null Retrieves the publicly_queryable argument for this post type.
	 */
	public function get_publicly_queryable(): ?bool {
		return $this->publicly_queryable;
	}

	/**
	 * @return bool|null Retrieves the show_ui argument for this post type.
	 */
	public function get_show_ui(): ?bool {
		return $this->show_ui;
	}

	/**
	 * @return bool|null Retrieves the show_in_menu argument for this post type.
	 */
	public function get_show_in_menu(): ?bool {
		return $this->show_in_menu;
	}

	/**
	 * @return bool|null Retrieves the show_in_nav_menus argument for this post type.
	 */
	public function get_show_in_nav_menus(): ?bool {
		return $this->show_in_nav_menus;
	}

	/**
	 * @return bool|null Retrieves the show_in_admin_bar argument for this post type.
	 */
	public function get_show_in_admin_bar(): ?bool {
		return $this->show_in_admin_bar;
	}

	/**
	 * @return bool|null Retrieves the show_in_rest argument for this post type.
	 */
	public function get_show_in_rest(): ?bool {
		return $this->show_in_rest;
	}

	/**
	 * @return string|null Retrieves the rest_base argument for this post type.
	 */
	public function get_rest_base(): ?string {
		return $this->rest_base;
	}

	/**
	 * @return string|null Retrieves the rest_namespace argument for this post type.
	 */
	public function get_rest_namespace(): ?string {
		return $this->rest_namespace;
	}

	/**
	 * @return string|null Retrieves the rest_controller_class argument for this post type.
	 */
	public function get_rest_controller_class(): ?string {
		return $this->rest_controller_class;
	}

	/**
	 * @return int|null Retrieves the menu_position argument for this post type.
	 */
	public function get_menu_position(): ?int {
		return $this->menu_position;
	}

	/**
	 * @return string|null Retrieves the menu_icon argument for this post type.
	 */
	public function get_menu_icon(): ?string {
		return $this->menu_icon;
	}

	/**
	 * @return array|null Retrieves the capability_type argument for this post type.
	 */
	public function get_capability_type(): ?array {
		return $this->capability_type;
	}

	/**
	 * @return string|null Retrieves the capabilities argument for this post type.
	 */
	public function get_capabilities(): ?string {
		return $this->capabilities;
	}

	/**
	 * @return bool|null Retrieves the has_meta_cap argument for this post type.
	 */
	public function get_has_meta_cap(): ?bool {
		return $this->has_meta_cap;
	}

	/**
	 * @return array|null Retrieves the supports argument for this post type.
	 */
	public function get_supports(): ?array {
		return $this->supports;
	}

	/**
	 * @return array|null Retrieves the taxonomies argument for this post type.
	 */
	public function get_taxonomies(): ?array {
		return $this->taxonomies;
	}

	/**
	 * @return bool|null Retrieves the has_archive argument for this post type.
	 */
	public function get_has_archive(): ?bool {
		return $this->has_archive;
	}

	/**
	 * @return bool|array|null Retrieves the has_rewrite argument for this post type.
	 */
	public function get_has_rewrite(): bool|array|null {
		return $this->has_rewrite;
	}

	/**
	 * @return string|null Retrieves the query_var argument for this post type.
	 */
	public function get_query_var(): ?string {
		return $this->query_var;
	}

	/**
	 * @return bool|null Retrieves the can_export argument for this post type.
	 */
	public function get_can_export(): ?bool {
		return $this->can_export;
	}

	/**
	 * @return bool|null Retrieves the delete_with_user argument for this post type.
	 */
	public function get_delete_with_user(): ?bool {
		return $this->delete_with_user;
	}

	/**
	 * @return array|null Retrieves the template argument for this post type.
	 */
	public function get_template(): ?array {
		return $this->template;
	}

	/**
	 * @return bool|null Retrieves the template_lock argument for this post type.
	 */
	public function get_template_lock(): ?bool {
		return $this->template_lock;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions(): void {
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'rest_' . $this->get_id() . '_query', [ $this, 'rest_query' ], 10, 2 );
	}

	/**
	 * Updates REST Requests to use prepared query arguments for REST Requests.
	 *
	 * @since 1.0.0
	 *
	 * @param array           $args
	 * @param WP_REST_Request $request
	 *
	 * @return array
	 */
	public function rest_query( array $args, WP_REST_Request $request ): array {
		return Array_Helper::merge( $args, $this->query()->to_array() );
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.0.0
	 * @throws WP_Error
	 */
	public function register() {
		$registered = register_post_type( $this->get_id(), Array_Helper::where_not_null( [
			'label'                 => $this->get_label(),
			'labels'                => $this->get_labels(),
			'description'           => $this->get_description(),
			'public'                => $this->get_public(),
			'hierarchical'          => $this->get_hierarchical(),
			'exclude_from_search'   => $this->get_exclude_from_search(),
			'publicly_queryable'    => $this->get_publicly_queryable(),
			'show_ui'               => $this->get_show_ui(),
			'show_in_menu'          => $this->get_show_in_menu(),
			'show_in_nav_menus'     => $this->get_show_in_nav_menus(),
			'show_in_admin_bar'     => $this->get_show_in_admin_bar(),
			'show_in_rest'          => $this->get_show_in_rest(),
			'rest_base'             => $this->get_rest_base(),
			'rest_namespace'        => $this->get_rest_namespace(),
			'rest_controller_class' => $this->get_rest_controller_class(),
			'menu_position'         => $this->get_menu_position(),
			'menu_icon'             => $this->get_menu_icon(),
			'capability_type'       => $this->get_capability_type(),
			'capabilities'          => $this->get_capabilities(),
			'has_meta_cap'          => $this->get_has_meta_cap(),
			'supports'              => $this->get_supports(),
			'taxonomies'            => $this->get_taxonomies(),
			'has_archive'           => $this->get_has_archive(),
			'has_rewrite'           => $this->get_has_rewrite(),
			'query_var'             => $this->get_query_var(),
			'can_export'            => $this->get_can_export(),
			'delete_with_user'      => $this->get_delete_with_user(),
			'template'              => $this->get_template(),
			'template_lock'         => $this->get_template_lock(),
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

	/**
	 * Run a WP_Query against this post type.
	 *
	 * @since 1.0.0
	 *
	 * @return Post_Query_Builder The Query object.
	 */
	public function query(): Post_Query_Builder {
		return ( new Post_Query_Builder( $this->query_instance ) )->set_type( $this->get_id() );
	}

	/**
	 * Sets up the builder to create an instance of this post type.
	 *
	 * @return Post_Builder
	 */
	public function get_builder(): Post_Builder {
		return ( new Post_Builder( $this->post_object_instance ) )->set_type( $this->get_id() );
	}

	/**
	 * Saves a post to the database.
	 *
	 * @throws WP_Error
	 */
	public function save( Post_Query_Builder|WP_Post $builder, bool $fire_after_hooks = true ): int {
		$args  = $builder->to_array();
		$saved = isset( $args['ID'] ) ? wp_update_post( $args, true, $fire_after_hooks ) : wp_insert_post( $args, true, $fire_after_hooks );

		if ( is_wp_error( $saved ) ) {
			throw new WP_Error( $saved );
		} else {
			Logger::notice(
				new Log_Item( code   : 'post_saved',
											message: 'A post was saved',
											data   : [ $args ]
				)
			);
		}

		return $saved;
	}

	/**
	 * Attempts to delete the provided post.
	 * If the post does not match this post type, this will return a WP_Error object.
	 *
	 * @param int  $id           The post ID.
	 * @param bool $force_delete Optional. Whether to bypass Trash and force deletion.
	 *                           Default false.
	 *
	 * @return WP_Post
	 * @throws Post_Not_Found
	 * @throws Invalid_Post_Type
	 * @throws Post_Delete_Failed
	 * @throws Post_Delete_Canceled
	 */
	public function delete( int $id, bool $force_delete ): WP_Post {

		$post_type = get_post_type( $id );

		if ( false === $post_type ) {
			throw new Post_Not_Found( $id );
		}

		if ( $post_type !== $this->get_id() ) {
			throw new Invalid_Post_Type( $id, $this->get_id() );
		}

		$deleted = wp_delete_post( $id, $force_delete );

		// Delete can literally return anything, so we have to be explicit here.
		if ( ! ( $deleted instanceof WP_Post && $id === $deleted->ID ) ) {

			// If the post returns something falsy, it's most-likely an error.
			if ( false === $deleted || null === $deleted ) {
				throw new Post_Delete_Failed( $id );
				// If we got something else, it's probably not an error.
			} else {
				throw new Post_Delete_Canceled( id: $id, data: [ 'result' => $deleted ] );
			}
		}

		Logger::notice(
			new Log_Item( 'post_deleted', 'A post was deleted', 'post_id', $this->get_id() )
		);

		return $deleted;
	}

	public function get_id(): string {
		return $this->id;
	}

}