<?php

namespace wcf\page;

// imports
use wcf\data\quiz\QuizList;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Class QuizListPage
 *
 * @package   de.teralios.QuizMaker
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
     * @throws \wcf\system\exception\SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        if (LanguageFactory::getInstance()->multilingualismEnabled()) {
            $languageIDs = WCF::getSession()->getLanguageIDs();

            $this->objectList->getConditionBuilder()->add('languageID IN (?' . str_repeat(', ?', count($languageIDs) - 1), $languageIDs);
        } else {
            $this->objectList->getConditionBuilder()->add('languageID = ?', [0]);
        }

        $this->objectList->getConditionBuilder()->add('isActive = ?', [1]);
    }
}
