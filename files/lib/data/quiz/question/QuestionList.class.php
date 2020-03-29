<?php
namespace wcf\data\quiz\question;

use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;

class QuestionList extends DatabaseObjectList
{
    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * QuestionList constructor.
     * @param Quiz|null $quiz
     * @throws \wcf\system\exception\SystemException
     */
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

    /**
     * @inheritDoc
     */
    protected function buildCondition()
    {
        $this->getConditionBuilder()->add('quizID = ?', [$this->quiz->quizID]);
    }
}
