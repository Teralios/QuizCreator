<?php

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;

/**
 * Class AJAXQuiz
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class AJAXQuiz extends Quiz
{
    /**
     * @var QuestionList
     */
    protected $questionList;

    /**
     * @var GoalList
     */
    protected $goalList;

    /**
     * AJAXQuiz constructor.
     * @param int $quizID
     * @param array|null $data
     * @param DatabaseObject|null $databaseObject
     */
    public function __construct(int $quizID, array $data = null, DatabaseObject $databaseObject = null)
    {
        parent::__construct($quizID, $data,$databaseObject);

        if ($this->quizID) {
            $this->initParts();
            $this->readAdditionalData();
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->data;
        $data['questions'] = [];
        $data['goals'] = [];

        $this->questionList->rewind();
        foreach ($this->questionList as $question) {
            $data['questions'][$question->position] = $question;
        }

        $this->goalList->rewind();
        foreach ($this->goalList as $goal) {
            $data['goals'][$goal->points] = $goal;
        }

        return $data;
    }

    /**
     * @throws \wcf\system\exception\SystemException
     */
    protected function initParts()
    {
        $this->questionList = new QuestionList($this);
        $this->questionList->readObjects();

        $this->goalList = new GoalList($this);
        $this->goalList->readObjects();
    }

    protected function readAdditionalData()
    {
        // reads results.
    }
}