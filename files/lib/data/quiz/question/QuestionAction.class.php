<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizEditor;

/**
 * Class QuestionAction
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuestionAction extends AbstractDatabaseObjectAction
{
    protected $className = QuestionEditor::class;
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
        $quizEditor->incrementCounter();

        return $question;
    }

    /**
     * @inheritDoc
     * @throws \wcf\system\database\exception\DatabaseQueryException
     * @throws \wcf\system\exception\SystemException
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
        foreach ($quizIDs as $quizID => $deleteQuestions) {
            QuestionEditor::updatePositionAfterDelete($quizID);
            QuizEditor::updateCounterAfterDelete($quizID, $deleteQuestions);
        }

        return $returnValue;
    }
}
