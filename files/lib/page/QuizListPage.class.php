<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\ViewableQuizList;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
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
    public $itemsPerPage = QUIZ_LIST_QUIZZES_PER_PAGE;
    public $objectListClassName = ViewableQuizList::class;
    public $defaultSortField = 'creationDate';
    public $validSortFields = ['title', 'creationDate'];
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];
    public $defaultSortOrder = 'DESC';

    /**
     * @var ViewableQuizList
     */
    public $objectList = null;

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
        $this->objectList->withMedia();
        $this->objectList->withStatistic();

        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            if (empty($this->languageID)) {
                $languageIDs = WCF::getSession()->getLanguageIDs();

                if (empty($languageIDs)) {
                    $languageIDs[] = LanguageFactory::getInstance()->getContentLanguageIDs();
                }

                $this->objectList->getConditionBuilder()->add(
                    $this->objectList->getDatabaseTableAlias() . '.languageID IN (?' . str_repeat(', ?', count($languageIDs) - 1) . ')',
                    $languageIDs
                );
            } else {
                $this->objectList->getConditionBuilder()->add(
                    $this->objectList->getDatabaseTableAlias() . '.languageID = ?',
                    [$this->languageID]
                );
            }
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
            $this->bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'best',
                'withQuiz' => true,
                'withUser' => true,
            ]);
        }

        if (QUIZ_LIST_LAST_PLAYERS) {
            $this->lastPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'last',
                'withQuiz' => true,
                'withUser' => true,
            ]);
        }

        if (QUIZ_LIST_MOST_PLAYED) {
            $this->mostPlayed = /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->getData();
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
