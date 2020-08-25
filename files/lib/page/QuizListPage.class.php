<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\ViewableQuizList;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Class QuizListPage
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizListPage extends SortablePage
{
    // inherit variables
    public $activeMenuItem = 'de.teralios.quizCreator.quizList';
    public $itemsPerPage = 25; // current hard coded
    public $objectListClassName = ViewableQuizList::class;
    public $defaultSortField = 'creationDate';
    public $validSortFields = ['title', 'creationDate'];
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];

    /**
     * @var bool
     */
    public $showCopyright = true;

    /**
     * @var int
     */
    public $languageID = 0;

    /**
     * @var GameList|null
     */
    public $lastPlayers = null;

    /**
     * @var GameList|null
     */
    public $bestPlayers = null;

    /**
     * @var ViewableQuizList|null;
     */
    public $mostPlayed = null;

    /**
     * @throws SystemException
     */
    public function readParameters()
    {
        parent::readParameters();

        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            $this->languageID = (isset($_REQUEST['languageID'])) ? (int)$_REQUEST['languageID'] : 0;
        }
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            if (empty($this->languageID)) {
                $languageIDs = WCF::getSession()->getLanguageIDs();

                $this->objectList->getConditionBuilder()->add('languageID IN (?' . str_repeat(', ?', count($languageIDs) - 1) . ')', $languageIDs);
            } else {
                $this->objectList->getConditionBuilder()->add('languageID = ?', [$this->languageID]);
            }
        }

        if (!WCF::getSession()->getPermission('admin.content.quizCreator.canManage')) {
            $this->objectList->getConditionBuilder()->add('isActive = ?', [1]);
        }
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function readData()
    {
        parent::readData();

        if (QUIZ_LIST_BEST_PLAYERS) {
            $this->bestPlayers = GameList::bestPlayers()->withQuiz()->withUser();
            $this->bestPlayers->readObjects();
        }

        if (QUIZ_LIST_LAST_PLAYERS) {
            $this->lastPlayers = GameList::lastPlayers()->withQuiz()->withUser();
            $this->lastPlayers->readObjects();
        }

        if (QUIZ_LIST_MOST_PLAYED) {
            $this->mostPlayed = new ViewableQuizList(true, false);
            $this->mostPlayed->sqlOrderBy = $this->mostPlayed->getDatabaseTableAlias() . '.played DESC';
            $this->mostPlayed->readObjects();
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'validSortFields' => $this->validSortFields,
            'languageID' => $this->languageID,
            'bestPlayers' => $this->bestPlayers,
            'lastPlayers' => $this->lastPlayers,
            'mostPlayed' => $this->mostPlayed,
            'showQuizMakerCopyright' => $this->showCopyright,
        ]);
    }
}
