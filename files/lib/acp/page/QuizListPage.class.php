<?php
namespace wcf\acp\page;

// imports
use wcf\data\quiz\QuizList;
use wcf\page\MultipleLinkPage;

class QuizListPage extends MultipleLinkPage
{
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $objectListClassName = QuizList::class;
    public $neededPermissions = ['admin.content.quizMaker.canManage'];
}
