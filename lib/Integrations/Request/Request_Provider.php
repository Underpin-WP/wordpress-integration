<?php
namespace Underpin\WordPress\Integrations\Request;

use Underpin\Factories\Header;
use Underpin\Factories\Request;
use Underpin\Factories\Url;
use Underpin\Interfaces\Feature_Extension;
use Underpin\WordPress\Integrations\Meta\WP_User_Identity;

class Provider implements Feature_Extension {

	public function __construct( public readonly Request $request ) {

	}

	function do_actions(): void {
		add_action( 'set_current_user', function () {
			if ( get_current_user_id() ) {
				$this->request->set_identity( new WP_User_Identity( wp_get_current_user() ) );
			}
		} );

		add_action( 'wp', function () {
			global $wp;

			foreach ( headers_list() as $header ) {
				$this->request->set_header( Header::from_string( $header ) );
			}

			$this->request->set_url( Url::from( home_url() )->set_path( add_query_arg( [], $wp->request ) ) );
		} );
	}

}