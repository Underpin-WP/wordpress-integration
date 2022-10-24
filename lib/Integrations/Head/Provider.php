<?php

namespace Underpin\WordPress\Integrations\Head;


use Underpin\Factories\Request;
use Underpin\Interfaces\Feature_Extension;
use Underpin\Registries\Head_Tag_Collection;

class Provider implements Feature_Extension {

	protected Request $request;

	public function __construct( protected Head_Tag_Collection $tags ) {
	}

	public function do_actions(): void {
		add_action( 'wp_head', [ $this, 'render_tags' ] );
	}

	public function render_tags() {
		echo implode( "\r\n", $this->tags->get_request_tags( $this->request )->to_array() );
	}

	public function set_request( Request $request ): static {
		$this->request = $request;

		return $this;
	}

}