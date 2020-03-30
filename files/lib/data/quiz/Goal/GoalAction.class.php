<?php
namespace wcf\data\quiz\goal;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\Quiz\Quiz;
use wcf\data\Quiz\QuizEditor;

class GoalAction extends AbstractDatabaseObjectAction
{
    protected $className = GoalEditor::class;
    protected $permissionsCreate = ['admin.content.quizMaker.canManage'];
    protected $permissionsUpdate = ['admin.content.quizMaker.canManage'];
    protected $permissionsDelete = ['admin.content.quizMaker.canManage'];

    /**
     * @inheritDoc
     * @throws \wcf\system\exception\SystemException
     */
    public function create()
    {
        $question = parent::create();

        // increment question counter
        $quizID = $question->quizID;
        $quizEditor = new QuizEditor(new Quiz($quizID));
        $quizEditor->incrementCounter(false);

        return $question;
    }
}
