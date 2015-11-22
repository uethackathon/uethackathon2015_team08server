<?php

class VoteController extends \Phalcon\Mvc\Controller {

	public function indexAction() {
	}

	public function upAction() {
		$response = new ApiResponse();
		$value    = 1;

		if ( $this->request->isGet() ) {
			$questions_id = $this->request->get( 'questions_id' );
			$answers_id   = $this->request->get( 'answers_id' );
			$users_id     = $this->request->get( 'users_id' );

			if ( $questions_id != null && $answers_id == null ) {
				$question = Questions::findFirstById( $questions_id );
				if ( $question == false ) {
					$response->setResponseError( 'Question not found!' );

					return $response;
				} else {
					if ( $question->users_id != $users_id
					     && ! Votes::checkIfVotedQuestion( $users_id, $questions_id )
					) {
						$question->upvotes += 1;
						if ( $question->save() == false ) {
							$response->setResponseError( $question->getMessages() );

							return $response;
						}

						$vote               = new Votes();
						$vote->id           = uniqid();
						$vote->questions_id = $questions_id;
						$vote->users_id     = $users_id;
						$vote->value        = $value;
						if ( $vote->save() == false ) {
							$response->setResponseError( $vote->getMessages() );

							return $response;
						} else {
							$response->setResponseMessage( "Create vote up $vote->id for question $questions_id successfully!" );
						}
					} else {
						$response->setResponseError( 'Cant re-vote or vote for yourself!' );

						return $response;
					}
				}
			} else if ( $questions_id == null && $answers_id != null ) {
				$answer = Answers::findFirstById( $answers_id );
				if ( $answer == false ) {
					$response->setResponseError( 'Answer not found!' );

					return $response;
				} else {
					if ( $answer->users_id != $users_id
					     && ! Votes::checkIfVotedAnswer( $users_id, $answers_id )
					) {
						$answer->upvotes += 1;
						if ( $answer->save() == false ) {
							$response->setResponseError( $answer->getMessages() );

							return $response;
						}

						$vote             = new Votes();
						$vote->id         = uniqid();
						$vote->answers_id = $answers_id;
						$vote->users_id   = $users_id;
						$vote->value      = $value;
						if ( $vote->save() == false ) {
							$response->setResponseError( $vote->getMessages() );

							return $response;
						} else {
							$response->setResponseMessage( $vote->id );
						}
					} else {
						$response->setResponseError( 'Cant re-vote or vote for yourself!' );

						return $response;
					}
				}
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

	public function downAction() {
		$response = new ApiResponse();
		$value    = - 1;

		if ( $this->request->isGet() ) {
			$questions_id = $this->request->get( 'questions_id' );
			$answers_id   = $this->request->get( 'answers_id' );
			$users_id     = $this->request->get( 'users_id' );

			if ( $questions_id != null && $answers_id == null ) {
				$question = Questions::findFirstById( $questions_id );
				if ( $question == false ) {
					$response->setResponseError( 'Question not found!' );

					return $response;
				} else {
					if ( $question->users_id != $users_id
					     && ! Votes::checkIfVotedQuestion( $users_id, $questions_id )
					) {
						$question->downvotes += 1;
						if ( $question->save() == false ) {
							$response->setResponseError( $question->getMessages() );

							return $response;
						}

						$vote               = new Votes();
						$vote->id           = uniqid();
						$vote->questions_id = $questions_id;
						$vote->users_id     = $users_id;
						$vote->value        = $value;
						if ( $vote->save() == false ) {
							$response->setResponseError( $vote->getMessages() );

							return $response;
						} else {
							$response->setResponseMessage( "Create vote down $vote->id for question $questions_id successfully!" );
						}
					} else {
						$response->setResponseError( 'Cant re-vote or vote for yourself!' );

						return $response;
					}
				}
			} else if ( $questions_id == null && $answers_id != null ) {
				$answer = Answers::findFirstById( $answers_id );
				if ( $answer == false ) {
					$response->setResponseError( 'Answer not found!' );

					return $response;
				} else {
					if ( $answer->users_id != $users_id
					     && ! Votes::checkIfVotedAnswer( $users_id, $answers_id )
					) {
						var_dump( Votes::checkIfVotedAnswer( $users_id, $answers_id ) );
						$answer->downvotes += 1;
						if ( $answer->save() == false ) {
							$response->setResponseError( $answer->getMessages() );

							return $response;
						}

						$vote             = new Votes();
						$vote->id         = uniqid();
						$vote->answers_id = $answers_id;
						$vote->users_id   = $users_id;
						$vote->value      = $value;
						if ( $vote->save() == false ) {
							$response->setResponseError( $vote->getMessages() );

							return $response;
						} else {
							$response->setResponseMessage( "Create vote down $vote->id for answer $answers_id successfully!" );
						}
					} else {
						$response->setResponseError( 'Cant re-vote or vote for yourself!' );

						return $response;
					}
				}
			}
		} else {
			$response->setResponseError( 'Wrong HTTP Method' );
		}

		return $response;
	}

}

