<?php

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class TagController extends \Phalcon\Mvc\Controller {

	public function indexAction() {
		$response = new ApiResponse();

		if ( $this->request->isGet() ) {
			$limit = $this->request->get( 'limit' );
			$page  = $this->request->get( 'page' );

			$questions = Tags::find();

			$paginator = new PaginatorModel(
				array(
					"data"  => $questions,
					"limit" => $limit,
					"page"  => $page
				)
			);

			$page = $paginator->getPaginate();
			$response->setResponse( $page->items, count( $questions ) );

			return $response;

		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function newAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$tag       = new Tags();
			$tag->id   = uniqid();
			$tag->name = $this->request->getPost( 'name' );

			if ( $this->request->hasFiles() == true ) {
				$baseLocation = 'files/';
				foreach ( $this->request->getUploadedFiles() as $file ) {
					$photos          = new Photos();
					$unique_filename = $tag->id;

					$photos->size          = $file->getSize();
					$photos->original_name = $file->getName();
					$photos->file_name     = $unique_filename;
					$photos->extension     = $file->getExtension();
					$location              = $baseLocation . $unique_filename . "." . $file->getExtension();
					$photos->public_link   = $location;

					try {
						if ( ! $photos->save() ) {
							$response->setResponseError( $photos->getMessages() );
						} else {
							//Move the file into the application
							$file->moveTo( $location );
							$tag->icon = $photos->public_link;
						}
					} catch ( PDOException $e ) {
						$response->setResponseError( $e->getMessage() );
					}
				}
			}

			try {
				if ( $tag->save() == false ) {
					$response->setResponseError( $tag->getMessages() );
				} else {
					$response->setResponseMessage( "Create tag $tag->id successfully!" );
				}
			} catch ( PDOException $e ) {
				$response->setResponseError( $e->getMessage() );
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

}

