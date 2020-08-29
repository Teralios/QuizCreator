<?php

namespace wcf\acp\page;

// imports
use wcf\data\quiz\ViewableQuizList;
use wcf\page\MultipleLinkPage;
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
class QuizListPage extends MultipleLinkPage
{
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.list';
    public $objectListClassName = ViewableQuizList::class;
    public $neededPermissions = ['admin.content.quizCreator.canManage'];

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function initObjectList()
    {
        parent::initObjectList();

        /** @scrutinizer ignore-call */
        $this->objectList->withMedia();
    }
    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign('isMultiLingual', /** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled());
    }
}
