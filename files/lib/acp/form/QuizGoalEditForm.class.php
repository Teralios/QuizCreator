<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\goal\Goal;
use wcf\data\quiz\Quiz;
use wcf\form\AbstractFormbuilderForm;
use wcf\system\exception\IllegalLinkException;

/**
 * Class QuizGoalEditForm
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizGoalEditForm extends QuizGoalAddForm
{
    // inherit vars
    public $formAction = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters(): void
    {
        AbstractFormBuilderForm::readParameters();

        $id = $_REQUEST['id'] ?? 0;
        $this->formObject = new Goal((int) $id);
        if (!$this->formObject->goalID) {
            throw new IllegalLinkException();
        }

        $this->quizObject = new Quiz($this->formObject->quizID);
    }
}
