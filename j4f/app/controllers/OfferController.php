<?php

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class OfferController extends \Phalcon\Mvc\Controller {

	public function indexAction() {
		$response = new ApiResponse();

		if ( $this->request->isGet() ) {
			$limit    = $this->request->get( 'limit' );
			$page     = $this->request->get( 'page' );
			$users_id = $this->request->get( 'users_id' );

			$offers = Offers::find( "users_id = '$users_id'" );

			if ( count( $offers ) == 0 ) {
				$response->setResponseError( 'No offers found!' );

				return $response;
			} else {
				$paginator = new PaginatorModel(
					array(
						"data"  => $offers,
						"limit" => $limit,
						"page"  => $page
					)
				);

				$page = $paginator->getPaginate();
				$response->setResponse( $page->items, count( $offers ) );

				return $response;
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
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

	public function bidAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$users_id  = $this->request->getPost( 'users_id' );
			$offers_id = $this->request->getPost( 'offers_id' );

			if ( $offers_id != null && $users_id != null ) {

				$offer = Offers::findFirstById( $offers_id );

				if ( $offer == true ) {
					$delimiter                = $offer->bid_users_list_id == null ? '' : ';';
					$offer->bid_users_list_id = $offer->bid_users_list_id . $delimiter . $users_id;

					try {
						if ( $offer->save() == false ) {
							$response->setResponseError( implode( ', ', $offer->getMessages() ) );
						} else {
							$response->setResponseMessage( "User $users_id bid offer $offers_id successfully!" );
						}
					} catch ( PDOException $e ) {
						$response->setResponseError( $e->getMessage() );
					}
				} else {
					$response->setResponseError( 'Not found offer!' );

					return $response;
				}
			} else {
				$response->setResponseError( 'Insufficient parameters' );

				return $response;
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function bidlistAction() {
		$response = new ApiResponse();

		if ( $this->request->isGet() ) {
			$offers_id = $this->request->get( 'offers_id' );
			$bids      = Offers::findFirstById( $offers_id );

			if ( $bids == false ) {
				$response->setResponseError( 'No bids found!' );

				return $response;
			} else {
				$list_bids = split( ';', $bids->bid_users_list_id );
				$response->setResponse( $list_bids, count( $list_bids ) );

				return $response;
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function updatestatusAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$users_id  = $this->request->getPost( 'users_id' );
			$offers_id = $this->request->getPost( 'offers_id' );

			if ( $offers_id != null && $users_id != null ) {

				$offer = Offers::findFirstById( $offers_id );

				if ( $offer == true ) {
					$offer->status          = 1; // accepted
					$offer->offered_user_id = $users_id;

					try {
						if ( $offer->save() == false ) {
							$response->setResponseError( implode( ', ', $offer->getMessages() ) );
						} else {
							$response->setResponseMessage( "User $users_id has been accepted for offer $offers_id!" );
						}
					} catch ( PDOException $e ) {
						$response->setResponseError( $e->getMessage() );
					}
				} else {
					$response->setResponseError( 'Not found offer!' );

					return $response;
				}
			} else {
				$response->setResponseError( 'Insufficient parameters' );

				return $response;
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

}

