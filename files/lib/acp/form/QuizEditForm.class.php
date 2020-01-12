<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;

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
    /**
     * @var string
     */
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';

    /**
     * @var array
     */
    public $neededPermissions = ['admin.content.quizMaker.canManage'];

    /**
     * WoltLab documentation is wrong!
     * $formAction are not set automatically to "edit" you must do it manually.
     *
     * @var string
     */
    public $formAction = 'edit';

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
}
