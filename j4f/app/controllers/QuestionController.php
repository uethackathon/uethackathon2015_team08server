<?php

class QuestionController extends \Phalcon\Mvc\Controller {

	public function indexAction() {

	}

	public function newAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$question           = new Questions();
			$question->id       = uniqid();
			$question->tags     = $this->request->getPost( 'tags' );
			$question->content  = $this->request->getPost( 'content' );
			$question->users_id = $this->request->getPost( 'users_id' );

			if ( $this->request->hasFiles() == true ) {
				$baseLocation = 'files/';
				foreach ( $this->request->getUploadedFiles() as $file ) {
					$photos          = new Photos();
					$unique_filename = $question->id;

					$photos->size          = $file->getSize();
					$photos->original_name = $file->getName();
					$photos->file_name     = $unique_filename;
					$photos->extension     = $file->getExtension();
					$location              = $baseLocation . $unique_filename . "." . $file->getExtension();
					$photos->public_link   = 'http://' . $_SERVER['HTTP_HOST'] . $this->getDI()->get( 'url' )->getBaseUri() . $location;

					try {
						if ( ! $photos->save() ) {
							$response->setResponseError( $photos->getMessages() );
						} else {
							//Move the file into the application
							$file->moveTo( $location );
							$question->image = $photos->public_link;
						}
					} catch ( PDOException $e ) {
						$response->setResponseError( $e->getMessage() );
					}
				}
			}

			try {
				if ( $question->save() == false ) {
					$response->setResponseError( $question->getMessages() );
				} else {
					$response->setResponseMessage( "Create question $question->id successfully!" );
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

