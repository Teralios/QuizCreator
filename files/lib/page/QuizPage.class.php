<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\ViewableQuiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Class QuizPage
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizPage extends AbstractPage
{
    /**
     * @var ViewableQuiz
     */
    public $quiz;

    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @var bool
     */
    public $showCopyright = true;

    /**
     * @var null|GameList
     */
    public $bestPlayers = null;

    /**
     * @var null|GameList
     */
    public $lastPlayers = null;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     * @throws PermissionDeniedException
     * @throws SystemException
     */
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

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function readData()
    {
        parent::readData();

        $this->bestPlayers = GameList::bestPlayers($this->quiz->getDecoratedObject())->withUser();
        $this->lastPlayers = GameList::lastPlayers($this->quiz->getDecoratedObject())->withUser();
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => $this->quiz,
            'showQuizMakerCopyright' => $this->showCopyright,
        ]);
    }
}
