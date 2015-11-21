<?php

class AnswerController extends \Phalcon\Mvc\Controller {

	public function indexAction() {

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
					$response->setResponseMessage( "Create answer $answer->id successfully!" );
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

