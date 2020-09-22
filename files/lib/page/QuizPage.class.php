<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\ViewableQuiz;
use wcf\data\tag\TagList;
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
class QuizPage extends AbstractPage
{
    // inherit vars
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];

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
     * @inheritDoc
     * @throws SystemException
     */
    public function readData()
    {
        parent::readData();

        if (QUIZ_BEST_PLAYERS) {
            $this->bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'best',
                'quizID' => $this->quiz->getObjectID(),
                'withUser' => true,
                'limit' => 10
            ]);
        }

        if (QUIZ_LAST_PLAYERS) {
            $this->lastPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'last',
                'quizID' => $this->quiz->getObjectID(),
                'withUser' => true,
                'limit' => 10
            ]);
        }

        if (MODULE_TAGGING) {
            $this->tags = /** @scrutinizer ignore-call */TagEngine::getInstance()->getObjectTags(Quiz::OBJECT_TYPE, $this->quiz->getObjectID());
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
            'activeTabMenuItem' => $this->activeTabMenuItem,
            'showQuizMakerCopyright' => $this->showCopyright,
        ]);
    }
}
