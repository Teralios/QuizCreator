<?php

namespace wcf\page;

// imports
use wcf\data\quiz\QuizList;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Class QuizListPage
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizListPage extends SortablePage
{
    // inherit variables
    public $activeMenuItem = 'de.teralios.quizMaker.quizList';
    public $itemsPerPage = 25; // current hard coded
    public $objectListClassName = QuizList::class;
    public $defaultSortField = 'creationDate';
    public $validSortFields = ['title', 'creationDate'];

    /**
     * @var int
     */
    public $languageID = 0;

    /**
     * @throws SystemException
     */
    public function readParameters()
    {
        parent::readParameters();

        if (LanguageFactory::getInstance()->multilingualismEnabled()) {
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

        if (LanguageFactory::getInstance()->multilingualismEnabled()) {
            if (empty($this->languageID)) {
                $languageIDs = WCF::getSession()->getLanguageIDs();

                $this->objectList->getConditionBuilder()->add('languageID IN (?' . str_repeat(', ?', count($languageIDs) - 1) . ')', $languageIDs);
            } else {
                $this->objectList->getConditionBuilder()->add('languageID = ?', [$this->languageID]);
            }
        }

        if (!WCF::getSession()->getPermission('admin.content.quizMaker.canManage')) {
            $this->objectList->getConditionBuilder()->add('isActive = ?', [1]);
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
            'languageID' => $this->languageID
        ]);
    }
}
