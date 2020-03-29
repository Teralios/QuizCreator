<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizEditor;
use wcf\system\database\exception\DatabaseTransactionException;
use wcf\system\WCF;

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

    /**
     * @inheritDoc
     */
    public function create()
    {
        $question = parent::create();

        // increment question counter
        $quizID = $question->quizID;
        $quizEditor = new QuizEditor(new Quiz($quizID));
        $quizEditor->incrementCounter();

        return $question;
    }

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
        foreach ($quizIDs as $quizID => $deleteQuestions) {
            QuestionEditor::updatePositionAfterDelete($quizID);
            QuizEditor::updateCounterAfterDelete($quizID, $deleteQuestions);
        }

        return $returnValue;
    }
}
