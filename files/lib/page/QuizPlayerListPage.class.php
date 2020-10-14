<?php

namespace wcf\page;

// imports
use wcf\data\quiz\game\GameList;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\WCF as WCF;

/**
 * Class        QuizPlayerListPage
 * @package     QuizCreator
 * @subpackage  wcf\page
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizPlayerListPage extends MultipleLinkPage
{
    // quiz page trait
    use TQuizPage;

    // inherit vars
    public $objectListClassName = GameList::class;

    /**
     * @var GameList
     */
    public $objectList = null;

    /**
     * @inheritdoc
     * @throws IllegalLinkException
     * @throws PermissionDeniedException
     * @throws SystemException
     */
    public function readParameters()
    {
        parent::readParameters();

        $this->readQuizParameters();
        $this->setQuizParentLocation();
    }

    /**
     * @inheritdoc
     * @throws SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        $this->objectList->withUser();
        $this->objectList->sqlOrderBy = $this->objectList->getDatabaseTableAlias() . '.score DESC';
        $this->objectList->sqlOrderBy .= ', ' . $this->objectList->getDatabaseTableAlias() . '.timeTotal ASC';
        $this->objectList->getConditionBuilder()->add(
            $this->objectList->getDatabaseTableAlias() . '.quizID = ?',
            [$this->quiz->quizID]
        );
    }

    /**
     * @inheritdoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        $this->assignQuizData();
        WCF::getTPL()->assign([
            'placementStart' => (1 + ($this->itemsPerPage * ($this->pageNo - 1)))
        ]);
    }
}
