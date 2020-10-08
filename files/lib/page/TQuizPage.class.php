<?php

namespace wcf\page;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\ViewableQuiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Trait        TQuizPage
 * @package     QuizCreator
 * @subpackage  wcf\page
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
trait TQuizPage
{
    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @var ViewableQuiz
     */
    public $quiz;

    public function readParameters()
    {
        parent::readParameters();

        $this->quizID = $_REQUEST['id'] ?? 0;

        $quiz = new Quiz((int) $this->quizID);
        if (!$quiz->quizID) {
            throw new IllegalLinkException();
        }

        if ($quiz->isActive == 0 && !WCF::getSession()->getPermission('admin.content.quizCreator.canManage')) {
            throw new PermissionDeniedException();
        }

        $this->quiz = new ViewableQuiz($quiz);
    }
}
