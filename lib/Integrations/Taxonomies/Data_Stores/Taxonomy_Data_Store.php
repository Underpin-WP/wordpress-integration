<?php

namespace Underpin\WordPress\Integrations\Taxonomies\Data_Stores;

use Underpin\Exceptions\Exception;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Factories\Log_Item;
use Underpin\Interfaces\Can_Create;
use Underpin\Interfaces\Can_Delete;
use Underpin\Interfaces\Can_Update;
use Underpin\Interfaces\Identifiable;
use Underpin\Interfaces\Identifiable_Int;
use Underpin\Interfaces\Model;
use Underpin\Registries\Logger;
use Underpin\WordPress\Abstracts\Model_To_Taxonomy_Adapter;
use Underpin\WordPress\Abstracts\Model_To_WP_Post_Adapter;
use Underpin\WordPress\Exceptions\Post_Delete_Canceled;
use Underpin\WordPress\Exceptions\Post_Delete_Failed;
use Underpin\WordPress\Exceptions\Term_Delete_Failed;
use WP_Post;

class Taxonomy_Data_Store implements Can_Create, Can_Update, Can_Delete {

	/**
	 * @param class-string<Model_To_Taxonomy_Adapter> $adapter
	 */
	public function __construct( protected string $adapter, protected string $taxonomy ) {

	}

	public function create( Model $model ): int|string {
		try {
			$response = wp_insert_term( ( new $this->adapter( $model ) )->to_array(), $this->taxonomy );

			if ( is_wp_error( $response ) ) {
				throw new Operation_Failed( $response->get_error_message() );
			}

			if ( $model instanceof Identifiable_Int ) {
				$model->set_id( (int) $response['term_id'] );
			}

			return $response['term_id'];
		} catch ( Exception $e ) {
			throw new Operation_Failed( $e->getMessage(), $e->getCode(), 'error', $e );
		}
	}

	public function update( Model $model ): int|string {
		try {
			$response = wp_insert_term( ( new $this->adapter( $model ) )->to_array(), $this->taxonomy );

			if ( is_wp_error( $response ) ) {
				throw new Operation_Failed( $response->get_error_message() );
			}

			return $response['term_id'];
		} catch ( Exception $e ) {
			throw new Operation_Failed( $e->getMessage(), $e->getCode(), 'error', $e );
		}
	}

	public function delete( int|string $id ): bool {
		$deleted = wp_delete_term( $id, $this->taxonomy );

			// If the post returns something falsy, it's most-likely an error.
			if ( false === $deleted ) {
				throw new Term_Delete_Failed( $id );
			}

		Logger::notice( new Log_Item( 'term_deleted', 'A term was deleted', 'term_id', $id ) );

		return true;
	}

}