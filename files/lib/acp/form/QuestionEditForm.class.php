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

        $id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
        $this->formObject = new Question((int) $id);
        if (!$this->formObject->questionID) {
            throw new IllegalLinkException();
        }

        $this->quizObject = new Quiz($this->formObject->quizID);
    }
}
