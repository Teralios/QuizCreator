<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\question\Question;
use wcf\data\quiz\Quiz;
use wcf\form\AbstractFormbuilderForm;
use wcf\system\exception\IllegalLinkException;

class QuestionEditForm extends QuestionAddForm
{
    // inherit vars
    public $formAction = 'edit';

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        AbstractFormBuilderForm::readParameters();

        $id = filter_input(INPUT_REQUEST, 'id', FILTER_VALIDATE_INT);
        $this->formObject = ($id !== null && $id !== false) ? new Question((int) $id) : null;
        if ($this->formObject === null || !$this->formObject->questionID) {
            throw new IllegalLinkException();
        }

        $this->quizObject = new Quiz($this->formObject->quizID);
    }
}
