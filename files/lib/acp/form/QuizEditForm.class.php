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
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $neededPermissions = ['admin.content.quizMaker.canManage'];

    /**
     * WoltLab documentation is wrong!
     * $formAction are not set automatically to "edit" you must do it manually.
     *
     * @var string
     */
    public $formAction = 'edit';

    public $questionList = null;

    /**
     * @inheritDoc
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

    public function readData()
    {
        parent::readData();

        $this->questionList = new QuestionList($this->formObject);
        $this->questionList->readObjects();
    }

    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'questionList' => $this->questionList
        ]);
    }
}
