<?php

class UserController extends \Phalcon\Mvc\Controller {

	public function indexAction() {

	}

	public function registerAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$account = new Users();

			$username     = $this->request->getPost( 'username' );
			$email    = $this->request->getPost( 'email' );
			$password = $this->request->getPost( 'password' );

			if ( ! isset( $username ) || ! isset( $password ) || ! isset( $email ) ) {
				$response->setResponseError( 'name, email and password can not be empty.' );

				return $response;
			}

			$account->id       = uniqid();
			$account->username = $username;
			$account->email    = $email;
			$account->password = $password;

			// Store the password hashed
			$account->password = $this->security->hash( $password );
			try {
				if ( $account->save() == false ) {
					$response->setResponseError( implode( ', ', $account->getMessages() ) );
				} else {
					$response->setResponse( $account->id );
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

