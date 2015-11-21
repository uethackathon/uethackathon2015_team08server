<?php

class Offers extends \Phalcon\Mvc\Model {

	/**
	 *
	 * @var string
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $tags;

	/**
	 *
	 * @var string
	 */
	public $content;

	/**
	 *
	 * @var string
	 */
	public $time;

	/**
	 *
	 * @var string
	 */
	public $title;

	/**
	 *
	 * @var string
	 */
	public $phone;

	/**
	 *
	 * @var integer
	 */
	public $status;

	/**
	 *
	 * @var integer
	 */
	public $users_id;

	/**
	 *
	 * @var string
	 */
	public $offered_user_id;

	/**
	 *
	 * @var string
	 */
	public $bid_users_list_id;

	/**
	 * Initialize method for model.
	 */
	public function initialize() {
		$this->belongsTo( 'users_id', 'Users', 'id', array( 'alias' => 'Users' ) );
	}

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'offers';
	}

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Offers[]
	 */
	public static function find( $parameters = null ) {
		return parent::find( $parameters );
	}

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Offers
	 */
	public static function findFirst( $parameters = null ) {
		return parent::findFirst( $parameters );
	}

}
