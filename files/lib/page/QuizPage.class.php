<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\Game;
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\question\QuestionList;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\ViewableQuiz;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\tagging\TagEngine;
use wcf\system\WCF;

/**
 * Class QuizPage
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizPage extends MultipleLinkPage
{
    // inherit vars
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];
    public $objectListClassName = GameList::class;

    /**
     * @var GameList
     */
    public $objectList = null;

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
     * @var Tag[]
     */
    public $tags = [];

    public $game = null;
    public $questions = null;

    /**
     * @var string
     */
    public $activeTabMenuItem = 'gameContainer';

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
     * @inheritdoc
     * @throws SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        $this->objectList->getConditionBuilder()->add(
            $this->objectList->getDatabaseTableAlias() . '.quizID = ?',
            [$this->quiz->quizID]
        );
        $this->objectList->withUser();
        $this->objectList->sqlOrderBy = $this->objectList->getDatabaseTableAlias() . '.scorePercent DESC';
        $this->objectList->sqlOrderBy .= ', ' . $this->objectList->getDatabaseTAbleAlias() . '.timeTotal ASC';
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function readData()
    {
        parent::readData();

        if (QUIZ_BEST_PLAYERS) {
            $this->bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'best',
                'quizID' => $this->quiz->quizID,
                'withUser' => true,
            ]);
        }

        if (QUIZ_LAST_PLAYERS) {
            $this->lastPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'last',
                'quizID' => $this->quiz->quizID,
                'withUser' => true,
            ]);
        }

        if (MODULE_TAGGING) {
            $this->tags = /** @scrutinizer ignore-call */TagEngine::getInstance()->getObjectTags(Quiz::OBJECT_TYPE, $this->quiz->getObjectID());
        }

        // questions and game of user
        if (WCF::getUser()->userID !== null) {
            $this->game = Game::getGame($this->quiz->getDecoratedObject(), WCF::getUser()->userID);

            if ($this->game->gameID) {
                $this->questions = new QuestionList($this->quiz->getDecoratedObject());
                $this->questions->readObjects();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => $this->quiz,
            'bestPlayers' => $this->bestPlayers,
            'lastPlayers' => $this->lastPlayers,
            'tags' => $this->tags,
            'game' => $this->game,
            'questions' => $this->questions,
            'activeTabMenuItem' => $this->activeTabMenuItem,
            'showQuizMakerCopyright' => $this->showCopyright,
        ]);
    }
}
