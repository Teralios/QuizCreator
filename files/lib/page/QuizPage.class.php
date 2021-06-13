<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\Game;
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\question\QuestionList;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\ViewableQuiz;
use wcf\data\tag\Tag;
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
    use TQuizPage;

    // inherit variables
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];

    /**
     * @var bool
     */
    public $showCopyright = true;

    /**
     * @var Tag[]
     */
    public $tags = [];

    /**
     * @var Game
     */
    public $game = null;

    /**
     * @var string
     */
    public $activeTabMenuItem = 'gameContainer';

    /**
     * @inheritdoc
     * @throws IllegalLinkException|PermissionDeniedException
     */
    public function readParameters(): void
    {
        parent::readParameters();

        $this->readQuizParameters();
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function readData(): void
    {
        parent::readData();

        if (MODULE_TAGGING) {
            $this->tags = /** @scrutinizer ignore-call */TagEngine::getInstance()->getObjectTags(Quiz::OBJECT_TYPE, $this->quiz->getObjectID());
        }

        $this->game = Game::getMatch($this->quiz, WCF::getUser()->userID);
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function assignVariables(): void
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => new ViewableQuiz($this->quiz),
            'tags' => $this->tags,
            'game' => $this->game,
            'activeTabMenuItem' => $this->activeTabMenuItem,
            'showQuizMakerCopyright' => $this->showCopyright
        ]);
    }
}
