<?php

namespace Underpin\WordPress\Integrations\Rest;


use Exception;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Middleware_Exception;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Interfaces\Identifiable;
use Underpin\Interfaces\Loader_Item;
use Underpin\Registries\Controller;
use Underpin\Registries\Rest_Action;
use Underpin\WordPress\Adapters\WP_Rest_Request_To_Request_Adapter;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class Item implements Feature_Extension, Identifiable, Loader_Item {

	public function __construct( protected Controller $controller ) {
	}

	public function do_actions(): void {
		register_rest_route( '', $this->controller->route, Array_Helper::each(
			$this->controller->to_array(),
			fn ( Rest_Action $action, string $method ) => [
				'methods'             => [ $method ],
				'callback'            => [ $this, 'get_response' ],
				'permission_callback' => [ $this, 'middleware' ],
			] )
		);
	}

	/**
	 * Gets the actual action for the request.
	 *
	 * @throws Middleware_Exception
	 */
	protected function get_action( WP_REST_Request $request ): Rest_Action {
		try {
			$request = ( new WP_Rest_Request_To_Request_Adapter( $request ) )->to_request();
			$type    = strtolower( $request->get_method()->value );

			try {
				/* @var $action Rest_Action */
				$action = new $this->controller->$type;
			} catch ( Exception $exception ) {
				throw new Item_Not_Found( 'Could not get rest action for type ' . $type . '.', 'error', $exception );
			}
		} catch ( Item_Not_Found|Operation_Failed $e ) {
			throw new Middleware_Exception( 'Something went wrong while getting the request action.', 500, previous: $e );
		}

		return $action->set_request( $request );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	protected function get_response( WP_REST_Request $request ): WP_REST_Response {
		try {
			$action = $this->get_action( $request );
			$action->do_actions();
			$response = $action->get_response();
		} catch ( Middleware_Exception $e ) {
			$response = new WP_Error( $e->getCode(), $e->getMessage() );
		}

		return rest_ensure_response( $response );
	}

	/**
	 * Handles middleware actions in permission check.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|bool
	 */
	protected function middleware( WP_REST_Request $request ): WP_Error|bool {
		try {
			$this->get_action( $request )->do_middleware_actions();
		} catch ( Middleware_Exception $exception ) {
			return new WP_Error( $exception->getCode(), $exception->getMessage() );
		}

		return true;
	}

	/**
	 * Gets the ID.
	 *
	 * @return string|int
	 */
	public function get_id(): string|int {
		return $this->controller->get_id();
	}

}