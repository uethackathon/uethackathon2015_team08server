<?php

class OfferController extends \Phalcon\Mvc\Controller {

	public function indexAction() {

	}

	public function newAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$offer           = new Offers();
			$offer->id       = uniqid();
			$offer->title    = $this->request->getPost( 'title' );
			$offer->tags     = $this->request->getPost( 'tags' );
			$offer->content  = $this->request->getPost( 'content' );
			$offer->time     = $this->request->getPost( 'time' );
			$offer->users_id = $this->request->getPost( 'users_id' );
			$offer->status   = 0;

			try {
				if ( $offer->save() == false ) {
					$response->setResponseError( $offer->getMessages() );
				} else {
					$response->setResponseMessage( "Create offer $offer->id successfully!" );
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

