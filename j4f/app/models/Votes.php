<?php

class Votes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $value;

    /**
     *
     * @var string
     */
    public $questions_id;

    /**
     *
     * @var string
     */
    public $answers_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('answers_id', 'Answers', 'id', array('alias' => 'Answers'));
        $this->belongsTo('questions_id', 'Questions', 'id', array('alias' => 'Questions'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'votes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Votes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Votes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
