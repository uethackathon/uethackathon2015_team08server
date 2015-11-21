<?php

class Photos extends \Phalcon\Mvc\Model {

	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var integer
	 */
	public $size;

	/**
	 *
	 * @var string
	 */
	public $original_name;

	/**
	 *
	 * @var string
	 */
	public $file_name;

	/**
	 *
	 * @var string
	 */
	public $extension;

	/**
	 *
	 * @var string
	 */
	public $public_link;

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'photos';
	}

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Photos[]
	 */
	public static function find( $parameters = null ) {
		return parent::find( $parameters );
	}

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Photos
	 */
	public static function findFirst( $parameters = null ) {
		return parent::findFirst( $parameters );
	}

}
