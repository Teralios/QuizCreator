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
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
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
     * @inheritDoc
     * @throws SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        if (LanguageFactory::getInstance()->multilingualismEnabled()) {
            $languageIDs = WCF::getSession()->getLanguageIDs();

            $this->objectList->getConditionBuilder()->add('languageID IN (?' . str_repeat(', ?', count($languageIDs) - 1), $languageIDs);
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
            'validSortFields' => $this->validSortFields
        ]);
    }
}
