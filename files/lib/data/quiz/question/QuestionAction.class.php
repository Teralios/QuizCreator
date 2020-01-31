<?php

namespace wcf\data\quiz\question;

// imports
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Class QuestionAction
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuestionAction extends AbstractDatabaseObjectAction
{
    protected $className = QuestionEditor::class;
    protected $permissionsCreate = ['admin.content.quizMaker.canManage'];
    protected $permissionsUpdate = ['admin.content.quizMaker.canManage'];
    protected $permissionsDelete = ['admin.content.quizMaker.canManage'];

    public function create()
    {

    }
}
