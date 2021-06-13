<?php

namespace wcf\data\quiz\goal;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObject;
use wcf\data\quiz\question\Question;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\exception\SystemException;

/**
 * Class GoalAction
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class GoalAction extends AbstractDatabaseObjectAction
{
    // inherit variables
    protected $className = GoalEditor::class;
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function create(): DatabaseObject
    {
        $goal = parent::create();

        // increment question counter
        /** @var Goal $goal */
        $quizID = $goal->quizID;
        $quizEditor = new QuizEditor(new Quiz($quizID));
        $quizEditor->incrementCounter(false);

        return $goal;
    }

    /**
     * @inheritDoc
     * @throws DatabaseQueryException|SystemException
     */
    public function delete()
    {
        $returnValue = parent::delete();

        // read quiz id
        $quizIDs = [];
        foreach ($this->objects as $question) {
            /** @var Question $question */
            $quizID = $question->quizID;

            if (isset($quizIDs[$quizID])) {
                $quizIDs[$quizID]++;
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
