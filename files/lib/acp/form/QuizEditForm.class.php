<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\question\QuestionList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Class QuizEditForm
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuizEditForm extends QuizAddForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $neededPermissions = ['admin.content.quizMaker.canManage'];
    public $formAction = 'edit';

    /**
     * @var QuestionList
     */
    public $questionList = null;

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     */
    public function readParameters()
    {
        parent::readParameters();

        // read quiz
        $id = filter_input(INPUT_REQUEST, 'id', FILTER_VALIDATE_INT);
        $this->formObject = ($id !== null && $id !== false) ? new Quiz($id) : null;
        if ($this->formObject === null || !$this->formObject->quizID) {
            throw new IllegalLinkException();
        }

        // success message
        $this->success = filter_input(INPUT_REQUEST, 'success', FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @inheritDoc
     */
    public function readData()
    {
        parent::readData();

        // read questions
        $this->questionList = new QuestionList($this->formObject);
        $this->questionList->readObjects();

        // add success message
        if ($this->success === true) {
            $this->form->showSuccessMessage(true);
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'questionList' => $this->questionList,
        ]);
    }
}
