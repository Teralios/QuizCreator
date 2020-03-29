<?php
namespace wcf\data\quiz\question;

use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;

class QuestionList extends DatabaseObjectList
{
    protected $quiz = null;

    public function __construct(Quiz $quiz = null)
    {
        parent::__construct();

        $this->quiz = $quiz;
        if ($this->quiz !== null) {
            $this->buildCondition();
        }

        // default order
        $this->sqlOrderBy = 'position ASC';
    }

    protected function buildCondition()
    {
        $this->getConditionBuilder()->add('quizID = ?', [$this->quiz->quizID]);
    }
}
