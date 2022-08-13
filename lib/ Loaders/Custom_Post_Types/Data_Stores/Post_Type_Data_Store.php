<?php

namespace Underpin\WordPress\Loaders\Custom_Post_Types\Data_Stores;

use Underpin\Exceptions\Exception;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Factories\Log_Item;
use Underpin\Interfaces\Can_Create;
use Underpin\Interfaces\Can_Delete;
use Underpin\Interfaces\Can_Update;
use Underpin\Interfaces\Model;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Exceptions\Post_Delete_Canceled;
use Underpin\WordPress\Exceptions\Post_Delete_Failed;
use Underpin\WordPress\Interfaces\Can_Convert_To_WP_Post;
use WP_Post;

class Post_Type_Data_Store implements Can_Create, Can_Update, Can_Delete {

	public function __construct( protected Can_Convert_To_WP_Post $adapter ) {

	}

	public function create( Model $model ): int|string {
		return $this->update( $model );
	}

	public function update( Model $model ): int|string {
		try {
			$response = wp_insert_post( $this->adapter->to_wp_post()->to_array(), true );

			if ( is_wp_error( $response ) ) {
				throw new Operation_Failed( $response->get_error_message() );
			}

			return $response;
		} catch ( Exception $e ) {
			throw new Operation_Failed( $e->getMessage(), $e->getCode(), 'error', $e );
		}
	}

	public function delete( int|string $id ): bool {
		$deleted = wp_delete_post( $id );

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


		Logger::notice( new Log_Item( 'post_deleted', 'A post was deleted', 'post_id', $id ) );

		return true;
	}
}