<?php

namespace Underpin\WordPress\Integrations\Rest;


use Exception;
use Underpin\Enums\Rest;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Middleware_Exception;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Helpers\String_Helper;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Interfaces\Identifiable;
use Underpin\Interfaces\Loader_Item;
use Underpin\Factories\Controller;
use Underpin\Abstracts\Rest_Action;
use Underpin\WordPress\Adapters\WP_Rest_Request_To_Request_Adapter;
use ValueError;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class Item implements Feature_Extension, Identifiable, Loader_Item {

	protected array $schemas = [];

	public function __construct( protected Controller $controller, protected string $namespace ) {
	}

	protected function get_route() {
		$pieces = explode( '/', $this->controller->route );
		$result = [];
		foreach ( $pieces as $piece ) {
			if ( String_Helper::starts_with( $piece, '$' ) ) {
				$result[] = '(?P<' . String_Helper::after( $piece, '$' ) . '>[\w-]+)';
			} else {
				$result[] = $piece;
			}
		}

		return String_Helper::prepend( implode( '/', $result ), '/' );
	}

	public function register_routes() {
		foreach ( $this->controller->to_array() as $method => $action ) {
			register_rest_route( $this->namespace, $this->get_route(), [
				[
					'methods'             => $method,
					'callback'            => [ $this, 'get_response' ],
					'permission_callback' => [ $this, 'middleware' ],
				],
			] );
		}
	}

	public function do_actions(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Gets the actual action for the request.
	 *
	 * @throws Middleware_Exception
	 * @throws ValueError
	 */
	protected function get_action( WP_REST_Request $request ): Rest_Action {

		if ( ! isset( $this->action ) ) {
			try {
				try {
					$type      = Rest::from( strtoupper( $request->get_method() ) );
					$action    = $this->controller->get_action( $type );
					$converted = ( new WP_Rest_Request_To_Request_Adapter( $request, $action->get_signature() ) )->to_request();
				} catch ( Exception $exception ) {
					throw new Item_Not_Found( 'Could not get rest action for type ' . $type->value . '.', 'error', $exception );
				}
			} catch ( Item_Not_Found|Operation_Failed $e ) {
				throw new Middleware_Exception( 'Something went wrong while getting the request action.', 500, previous: $e );
			}
			$this->action = $action->set_request( $converted );
		}

		return $this->action;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function get_response( WP_REST_Request $request ): WP_REST_Response {
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
	public function middleware( WP_REST_Request $request ): WP_Error|bool {

		try {
			$this->get_action( $request )->do_middleware_actions();
		} catch ( Middleware_Exception $exception ) {
			return new WP_Error( $exception->getCode(), $exception->getMessage() );
		} catch ( ValueError $e ) {
			// Let WordPress deal with unknown request types.
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