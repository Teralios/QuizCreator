<?php

namespace wcf\data\quiz\question;

use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\SystemException;

/**
 * Class QuestionList
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
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

        // default order
        $this->sqlOrderBy = 'position ASC';
    }
}
