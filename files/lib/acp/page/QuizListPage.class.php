<?php
namespace wcf\acp\page;

// imports
use wcf\data\quiz\QuizList;
use wcf\page\MultipleLinkPage;
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
class QuizListPage extends MultipleLinkPage
{
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $objectListClassName = QuizList::class;
    public $neededPermissions = ['admin.content.quizMaker.canManage'];

    /**
     * @inheritDoc
     * @throws \wcf\system\exception\SystemException
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign('isMultiLingual', LanguageFactory::getInstance()->multilingualismEnabled());
    }
}
