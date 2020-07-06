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
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuizQuestionEditForm extends QuizQuestionAddForm
{
    // inherit vars
    public $formAction = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        AbstractFormBuilderForm::readParameters();

        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
        $this->formObject = new Question((int) $id);
        if (!$this->formObject->questionID) {
            throw new IllegalLinkException();
        }

        $this->quizObject = new Quiz($this->formObject->quizID);
    }
}
