<?php

namespace AppBundle;


use AppBundle\Service\AppService;

class TestSession
{
    /** @var string */
    private $username = '';

    /** @var array  */
    private $words = array();

    /** @var int */
    private $valid = 0;

    /** @var int  */
    private $errors = 0;

    /**
     * TestSession constructor.
     * @param string $username
     */
    public function __construct($username = '')
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param array $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param int $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param int $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    /**
     * @param $word
     */
    public function addWord($word)
    {
        $this->words[] = $word;
    }

    /**
     * @return void
     */
    public function addValid()
    {
        $this->valid++;
    }

    /**
     * @return void
     */
    public function addError()
    {
        $this->errors++;
    }

    /**
     * @return int
     */
    public function getScores()
    {
        return $this->valid * AppService::CORRECT_ANSWER_SCORE;
    }
}