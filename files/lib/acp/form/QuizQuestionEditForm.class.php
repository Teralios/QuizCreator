<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\question\Question;
use wcf\data\quiz\Quiz;
use wcf\form\AbstractFormbuilderForm;
use wcf\system\exception\IllegalLinkException;

/**
 * Class QuizQuestionEditForm
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizQuestionEditForm extends QuizQuestionAddForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $formAction = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        AbstractFormBuilderForm::readParameters();

        $id = $_REQUEST['id'] ?? 0;
        $this->formObject = new Question((int) $id);
        if (!$this->formObject->questionID) {
            throw new IllegalLinkException();
        }

        $this->quizObject = new Quiz($this->formObject->quizID);
    }
}
