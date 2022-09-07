<?php

namespace Underpin\WordPress\Adapters;

use Underpin\Enums\Rest;
use Underpin\Enums\Types;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Exceptions\Url_Exception;
use Underpin\Factories\Header;
use Underpin\Factories\Registry_Items\Param;
use Underpin\Factories\Url;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Can_Convert_To_Request;
use WP_REST_Request;
use Underpin\Factories\Request;

class WP_Rest_Request_To_Request_Adapter implements Can_Convert_To_Request {

	/**
	 * @param WP_REST_Request $original
	 * @param Param[]     $signature
	 */
	public function __construct( protected WP_REST_Request $original, protected array $signature ) {
	}

	/**
	 * Converts query params to URL Param objects.
	 *
	 * @param array   $params
	 * @param Types[] $param_signature
	 *
	 * @return array
	 */
	protected function hydrate_params( array $params ): array {
		$result = [];
		foreach ( $params as $key => $param ) {
			if ( isset( $this->signature[ $key ] ) ) {
				$item = $this->signature[ $key ];
				settype($param, $item->get_type()->value);
				$result[] = $item->set_value($param);
			} else {
				$result[] = (new Param( $param, Types::from( gettype( $param ) ) ))->set_value($param);
			}
		}

		return $result;
	}

	/**
	 * Gets the URL
	 *
	 * @throws Operation_Failed
	 * @throws Url_Exception
	 */
	protected function get_url(): Url {
		$url = Url::from( get_rest_url() )->set_path( $this->original->get_route() );

		foreach ( $this->hydrate_params( $this->original->get_params() ) as $param ) {
			$url->add_param( $param );
		}

		return $url;
	}

	/**
	 * Gets the header s from the request.
	 *
	 * @return array
	 */
	protected function get_headers(): array {
		return Array_Helper::each(
			$this->original->get_headers(),
			fn ( mixed $value, string $key ) => ( new Header( $key ) )->set_value( $value )
		);
	}

	protected function get_ip(): string {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 * @throws Operation_Failed
	 */
	function to_request(): Request {
		try {
			$request = ( new Request )
				->set_url( $this->get_url() )
				->set_body( $this->original->get_body() )
				->set_ip( $this->get_ip() )
				->set_method( Rest::from( strtoupper( $this->original->get_method() ) ) );
		} catch ( Url_Exception $e ) {
			throw new Operation_Failed( 'Could not create URL.', previous: $e );
		}

		foreach ( $this->get_headers() as $header ) {
			$request->set_header( $header );
		}

		return $request;
	}

}