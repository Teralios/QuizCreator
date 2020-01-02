<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;

class QuizEditForm extends QuizAddForm
{
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';

    // Documentation on docs.woltlab.com is wrong. YOU MUST SET this variable!
    public $formAction = 'edit';

    /**
     * @throws IllegalLinkException
     */
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
