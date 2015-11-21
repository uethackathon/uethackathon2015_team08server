<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Users extends \Phalcon\Mvc\Model {

	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $username;

	/**
	 *
	 * @var string
	 */
	public $email;

	/**
	 *
	 * @var string
	 */
	public $password;

	/**
	 *
	 * @var string
	 */
	public $fbId;

	/**
	 *
	 * @var integer
	 */
	public $credit;

	/**
	 *
	 * @var integer
	 */
	public $reputation;

	/**
	 *
	 * @var integer
	 */
	public $rank;

	/**
	 *
	 * @var string
	 */
	public $phone;

	/**
	 *
	 * @var string
	 */
	public $avatar;

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation() {
		$this->validate(
			new Email(
				array(
					'field'    => 'email',
					'required' => true,
				)
			)
		);

		$this->validate( new Uniqueness( array(
					"field"   => "email",
					"message" => "Value of field 'email' is already present in another record"
				)
			)
		);

		if ( $this->validationHasFailed() == true ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize method for model.
	 */
	public function initialize() {
		$this->hasMany( 'id', 'Answers', 'users_id', array( 'alias' => 'Answers' ) );
		$this->hasMany( 'id', 'Offers', 'users_id', array( 'alias' => 'Offers' ) );
		$this->hasMany( 'id', 'Questions', 'users_id', array( 'alias' => 'Questions' ) );
	}

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'users';
	}

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Users[]
	 */
	public static function find( $parameters = null ) {
		return parent::find( $parameters );
	}

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Users
	 */
	public static function findFirst( $parameters = null ) {
		return parent::findFirst( $parameters );
	}

}
