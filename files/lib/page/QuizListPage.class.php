<?php

namespace wcf\page;

// imports
use wcf\data\category\Category;
use wcf\data\category\CategoryNodeTree;
use wcf\data\quiz\category\QuizCategory;
use wcf\data\quiz\game\GameList;
use wcf\data\quiz\ViewableQuizList;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\IllegalLinkException;
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
    public $validSortFields = ['creationDate'];
    public $neededPermissions = ['user.quiz.canView'];
    public $neededModules = ['MODULE_QUIZ_CREATOR'];
    public $defaultSortOrder = 'DESC';
    public $categoryID = null;

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
     * @var ?GameList
     */
    public $lastPlayers = null;

    /**
     * @var ?GameList
     */
    public $bestPlayers = null;

    /**
     * @var ?ViewableQuizList
     */
    public $mostPlayed = null;

    /**
     * @var CategoryNodeTree
     * @since 1.5
     */
    public $categoryList;

    /**
     * @var ?QuizCategory
     */
    public $category = null;

    /**
     * @var ?GameList
     */
    public $quizPlayed = null;

    /**
     * @throws SystemException
     * @throws IllegalLinkException
     */
    public function readParameters()
    {
        parent::readParameters();

        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            $this->languageID = (isset($_REQUEST['languageID'])) ? (int)$_REQUEST['languageID'] : 0;
        }

        // 1.5 code start
        if (isset($_REQUEST['categoryID'])) {
            $this->categoryID = (int)$_REQUEST['categoryID'];
            $this->category = /** @scrutinizer ignore-call */CategoryHandler::getInstance()->getCategory($this->categoryID);
            if (!$this->category->categoryID) {
                throw new IllegalLinkException();
            }
        }

        $this->categoryList = new CategoryNodeTree(QuizCategory::OBJECT_TYPE);
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
        $this->objectList->withUserStatus();

        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            if (empty($this->languageID)) {
                $languageIDs = WCF::getSession()->getLanguageIDs();

                if (empty($languageIDs)) {
                    $languageIDs[] = /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getContentLanguageIDs();
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
            // 1.5 code
            'categoryList' => $this->categoryList->getIterator(),
            'category' => $this->category
        ]);
    }
}
