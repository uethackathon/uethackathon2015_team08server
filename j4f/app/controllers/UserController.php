<?php

class UserController extends \Phalcon\Mvc\Controller {

	public function indexAction() {

	}

	public function registerAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$user = new Users();

			$username = $this->request->getPost( 'username' );
			$email    = $this->request->getPost( 'email' );
			$password = $this->request->getPost( 'password' );

			if ( ! isset( $username ) || ! isset( $password ) || ! isset( $email ) ) {
				$response->setResponseError( 'name, email and password can not be empty.' );

				return $response;
			}

			$user->id       = uniqid();
			$user->username = $username;
			$user->email    = $email;
			$user->password = $password;
//			$user->avatar =

			// Store the password hashed
			$user->password = $this->security->hash( $password );
			try {
				if ( $user->save() == false ) {
					$response->setResponseError( implode( ', ', $user->getMessages() ) );
				} else {
					$response->setResponse( array(
						'id'       => $user->id,
						'username' => $user->username,
						'email'    => $user->email,
						'avatar'   => $user->avatar
					) );
				}
			} catch ( PDOException $e ) {
				$response->setResponseError( $e->getMessage() );
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function loginAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$email    = $this->request->getPost( 'email' );
			$password = $this->request->getPost( 'password' );

			// Check if the user exist
			$user = Users::findFirstByEmail( $email );
			if ( $user == false ) {
				$response->setResponseError( 'Wrong email/password combination 1' );

				return $response;
			}

			// Check the password
			if ( ! $this->security->checkHash( $password, $user->password ) ) {
				$response->setResponseError( 'Wrong email/password combination' );

				return $response;
			}

			$response->setResponse( array(
				'id'       => $user->id,
				'username' => $user->username,
				'email'    => $user->email,
				'avatar'   => $user->avatar
			) );
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function loginFbAction() {
		$response = new ApiResponse();

		if ( $this->request->isPost() ) {
			$fbId     = $this->request->getPost( 'fbId' );
			$username = $this->request->getPost( 'username' );
			$email    = $this->request->getPost( 'email' );
			$avatar   = $this->request->getPost( 'avatar' );

			$user = Users::findFirstByFbId( $fbId );

			if ( $user == true ) {
				$response->setResponseMessage( 'Login successfully!' );

				return $response;
			} else {
				$user           = new Users();
				$user->id       = uniqid();
				$user->fbId     = $fbId;
				$user->avatar   = $avatar;
				$user->username = $username;
				$user->email    = $email;
				try {
					if ( $user->save() == false ) {
						$response->setResponseError( implode( ', ', $user->getMessages() ) );
					} else {
						$response->setResponseMessage( 'Register successfully!' );
					}
				} catch ( PDOException $e ) {
					$response->setResponseError( $e->getMessage() );
				}
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}
}

