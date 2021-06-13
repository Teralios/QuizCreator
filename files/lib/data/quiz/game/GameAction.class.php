<?php

namespace wcf\data\quiz\game;

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
class GameAction extends AbstractDatabaseObjectAction
{
    protected $permissionsShowResult = ['user.quiz.canView'];
    protected $className = GameEditor::class;

    /**
     * @var Game
     */
    protected $game = null;

    /**
     * Validate show result.
     * @throws PermissionDeniedException|UserInputException
     */
    public function validateShowResult(): void
    {
        WCF::getSession()->checkPermissions($this->permissionsShowResult);

        $this->game = $this->getSingleObject();
        if ($this->game instanceof DatabaseObjectDecorator) {
            $this->game =  /** @scrutinizer ignore-call */ $this->game->getDecoratedObject();
        }
    }

    /**
     * Returns show result.
     * @return string
     * @throws SystemException
     */
    public function showResult(): string
    {
        $quiz = new Quiz($this->game->quizID);
        $questions = new QuestionList($quiz);
        $questions->readObjects();

        WCF::getTPL()->assign([
            'game' => $this->game,
            'quiz' => $quiz,
            'questions' => $questions,
        ]);

        return WCF::getTPL()->fetch('__quizDialogUser');
    }
}
