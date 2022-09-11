<?php

namespace Underpin\WordPress\Integrations\Head;


use Underpin\Factories\Request;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Registries\Head_Tag_Collection;

class Provider implements Feature_Extension {

	public function __construct( protected Head_Tag_Collection $tags, protected Request $request ) {}

		public function do_actions(): void {
		add_action( 'wp_head', function () {
			echo implode( "\r\n", $this->tags->get_request_tags( $this->request )->to_array() );
		} );
	}

}