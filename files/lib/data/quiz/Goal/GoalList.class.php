<?php
namespace wcf\data\quiz\goal;

// imports
use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;

/**
 * Class GoalList
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class GoalList extends DatabaseObjectList
{
    // inherit vars
    public $className = Goal::class;

    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * GoalList constructor.
     * @param Quiz|null $quiz
     * @throws \wcf\system\exception\SystemException
     */
    public function __construct(Quiz $quiz = null)
    {
        parent::__construct();

        if ($quiz !== null) {
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

        // default sort order
        $this->sqlOrderBy = 'points DESC';
    }
}