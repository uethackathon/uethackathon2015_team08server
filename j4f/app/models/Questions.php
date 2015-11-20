<?php

class Questions extends \Phalcon\Mvc\Model {

	/**
	 *
	 * @var integer
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
	public $image;

	/**
	 *
	 * @var integer
	 */
	public $upvotes;

	/**
	 *
	 * @var integer
	 */
	public $downvotes;

	/**
	 *
	 * @var string
	 */
	public $created_at;

	/**
	 *
	 * @var string
	 */
	public $modified_in;

	/**
	 *
	 * @var integer
	 */
	public $users_id;

	/**
	 * Initialize method for model.
	 */
	public function initialize() {
		$this->hasMany( 'id', 'Answers', 'questions_id', array( 'alias' => 'Answers' ) );
		$this->belongsTo( 'users_id', 'Users', 'id', array( 'alias' => 'Users' ) );
	}

	/**
	 * Returns table name mapped in the model.
	 *
	 * @return string
	 */
	public function getSource() {
		return 'questions';
	}

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Questions[]
	 */
	public static function find( $parameters = null ) {
		return parent::find( $parameters );
	}

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param mixed $parameters
	 *
	 * @return Questions
	 */
	public static function findFirst( $parameters = null ) {
		return parent::findFirst( $parameters );
	}

}
