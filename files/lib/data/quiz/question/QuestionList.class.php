<?php

namespace wcf\data\quiz\question;

use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\SystemException;

/**
 * Class QuestionList
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuestionList extends DatabaseObjectList
{
    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * QuestionList constructor.
     * @param Quiz|null $quiz
     * @throws SystemException
     */
    public function __construct(Quiz $quiz = null)
    {
        parent::__construct();

        if ($this->quiz !== null) {
            $this->quiz = $quiz;
            $this->defaultCommand();
        }
    }

    /**
     * Build standard condition.
     */
    protected function defaultCommand()
    {
        $this->getConditionBuilder()->add('quizID = ?', [$this->quiz->quizID]);

        // default order
        $this->sqlOrderBy = 'position ASC';
    }
}
