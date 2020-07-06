<?php

namespace wcf\data\quiz\goal;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\Quiz\Quiz;
use wcf\data\Quiz\QuizEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\exception\SystemException;

/**
 * Class GoalAction
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class GoalAction extends AbstractDatabaseObjectAction
{
    protected $className = GoalEditor::class;
    protected $permissionsCreate = ['admin.content.quizMaker.canManage'];
    protected $permissionsUpdate = ['admin.content.quizMaker.canManage'];
    protected $permissionsDelete = ['admin.content.quizMaker.canManage'];

    /**
     * @inheritDoc
     * @throws SystemException
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

    /**
     * @inheritDoc
     * @throws DatabaseQueryException
     * @throws SystemException
     */
    public function delete()
    {
        $returnValue = parent::delete();

        // read quiz id
        $quizIDs = [];
        foreach ($this->objects as $question) {
            $quizID = $question->quizID;

            if (isset($quizIDs[$quizID])) {
                $quizIDs[$quizID] += 1;
            } else {
                $quizIDs[$quizID] = 1;
            }
        }

        // update position
        foreach ($quizIDs as $quizID => $deleteGoals) {
            QuizEditor::updateCounterAfterDelete($quizID, $deleteGoals, false);
        }

        return $returnValue;
    }
}
