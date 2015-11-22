<?php

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class AnswerController extends \Phalcon\Mvc\Controller {

	public function indexAction() {
		$response = new ApiResponse();

		if ( $this->request->isGet() ) {
			$limit        = $this->request->get( 'limit' );
			$page         = $this->request->get( 'page' );
			$questions_id = $this->request->get( 'questions_id' );

			$answers = Answers::find( array( "questions_id = '$questions_id'", "order" => "created_at DESC" ) );

			$paginator = new PaginatorModel(
				array(
					"data"  => $answers,
					"limit" => $limit,
					"page"  => $page
				)
			);

			$page = $paginator->getPaginate();
//			$response->setResponse( $page->items, count( $answers ) );

			$res = [ ];
			foreach ( $page->items as $item ) {
				$user = Users::findFirst( $item->users_id );

				$res[] = array(
					'id'           => $item->id,
					'content'      => $item->content,
					'photo'        => $item->photo,
					'questions_id' => $item->questions_id,
					'users_id'     => $item->users_id,
					'avatar'       => $user->avatar,
					'username'     => $user->username,
					'email'        => $user->email,
				);
			}

			$response->setResponse( $res, count( $answers ) );

			return $response;

		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function newAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$answer               = new Answers();
			$answer->id           = uniqid();
			$answer->content      = $this->request->getPost( 'content' );
			$answer->users_id     = $this->request->getPost( 'users_id' );
			$answer->questions_id = $this->request->getPost( 'questions_id' );

			if ( $this->request->hasFiles() == true ) {
				$baseLocation = 'files/';
				foreach ( $this->request->getUploadedFiles() as $file ) {
					$photos          = new Photos();
					$unique_filename = $answer->id;

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
							$answer->photo = $photos->public_link;
						}
					} catch ( PDOException $e ) {
						$response->setResponseError( $e->getMessage() );
					}
				}
			}

			try {
				if ( $answer->save() == false ) {
					$response->setResponseError( $answer->getMessages() );
				} else {
					$response->setResponse( $answer->id );
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

