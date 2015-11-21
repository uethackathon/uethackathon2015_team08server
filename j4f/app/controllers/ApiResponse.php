<?php
/**
 * Created by nvg58 on 9/21/15
 *
 */

use Phalcon\Http\Response;

class ApiResponse extends Response {
	public function __construct( $content = null, $code = null, $status = null ) {
		parent::__construct( $content, $code, $status );

		$this->setContentType( "application/json", "UTF-8" );
	}

	public function setResponse( $data, $count = null ) {
		$this->setStatusCode( 200, 'OK' );

		$this->setJsonContent( array(
			'status' => 'ok',
			'count'  => $count,
			'data'   => $data
		) );
	}

	public function setResponseMessage( $data ) {
		$this->setStatusCode( 200, 'OK' );

		$this->setJsonContent( array(
			'status' => 'ok',
			'data'   => $data
		) );
	}

	public function setResponseError( $message ) {
		$this->setStatusCode( 200, 'OK' );

		if ( is_array( $message ) ) {
			$message = implode( ', ', $message );
		}

		$this->setJsonContent( array(
			'status'  => 'error',
			'message' => $message
		) );
	}
}