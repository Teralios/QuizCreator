<?php

namespace wcf\data\quiz\match;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\quiz\question\QuestionList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

/**
 * Class        GameAction
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class MatchAction extends AbstractDatabaseObjectAction
{
    protected $permissionsShowResult = ['user.quiz.canView'];

    /**
     * @var Match
     */
    protected $match = null;

    /**
     * Validate show result.
     * @throws PermissionDeniedException
     * @throws UserInputException
     */
    public function validateShowResult()
    {
        WCF::getSession()->checkPermissions($this->permissionsShowResult);

        $this->match = $this->getSingleObject();
        if ($this->match instanceof DatabaseObjectDecorator) {
            $this->match =  /** @scrutinizer ignore-call */ $this->match->getDecoratedObject();
        }
    }

    /**
     * Returns show result.
     * @return string
     * @throws SystemException
     */
    public function showResult()
    {
        $quiz = new Quiz($this->match->quizID);
        $questions = new QuestionList($quiz);
        $questions->readObjects();

        WCF::getTPL()->assign([
            'match' => $this->match,
            'quiz' => $quiz,
            'questions' => $questions,
        ]);

        return WCF::getTPL()->fetch('__quizUserResult');
    }
}
