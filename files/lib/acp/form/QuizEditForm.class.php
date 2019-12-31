<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;

class QuizEditForm extends QuizAddForm
{
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    /*
     * Documentation from WoltLab said this:
     * $formAction is added and set to create as the form is used to create a new person. In the edit form,
     * $formAction has not to be set explicitly as it is done automatically if a $formObject is set.
     *
     * But... where?
     */
    public $formAction = 'edit';
    public function readParameters()
    {
        parent::readParameters();
        if (isset($_REQUEST['id'])) {
            $this->formObject = new Quiz(intval($_REQUEST['id']));
            if (!$this->formObject->quizID) {
                throw new IllegalLinkException();
            }
        }
    }
}
