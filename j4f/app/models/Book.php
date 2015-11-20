<?php

class Book extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $ISBN;

    /**
     *
     * @var string
     */
    public $author;

    /**
     *
     * @var string
     */
    public $short_description;

    /**
     *
     * @var integer
     */
    public $page_number;

    /**
     *
     * @var string
     */
    public $publisher;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Transaction', 'book_id', array('alias' => 'Transaction'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'book';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Book[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Book
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
