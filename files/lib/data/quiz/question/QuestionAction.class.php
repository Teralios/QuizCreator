<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\exception\SystemException;

/**
 * Class QuestionAction
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuestionAction extends AbstractDatabaseObjectAction
{
    protected $className = QuestionEditor::class;
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function create()
    {
        /** @var Question $question */
        $question = parent::create();

        // increment question counter
        $quizID = $question->quizID;
        $quizEditor = new QuizEditor(new Quiz($quizID));
        $quizEditor->incrementCounter();

        return $question;
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
