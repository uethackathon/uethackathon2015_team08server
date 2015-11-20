<?php

class Transaction extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $student_id;

    /**
     *
     * @var string
     */
    public $librarian_id;

    /**
     *
     * @var string
     */
    public $book_id;

    /**
     *
     * @var string
     */
    public $action;

    /**
     *
     * @var string
     */
    public $time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('book_id', 'Book', 'id', array('alias' => 'Book'));
        $this->belongsTo('librarian_id', 'Librarian', 'id', array('alias' => 'Librarian'));
        $this->belongsTo('student_id', 'Student', 'id', array('alias' => 'Student'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'transaction';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Transaction
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
