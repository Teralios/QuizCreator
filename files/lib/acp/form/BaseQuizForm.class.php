<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Class BaseQuizForm
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class BaseQuizForm extends AbstractFormBuilderForm
{
    /**
     * @var Quiz
     */
    public $quizObject = null;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     */
    public function readParameters()
    {
        parent::readParameters();

        $quizID = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
        if ($quizID == 0) {
            $quizID = (isset($_REQUEST['quizID'])) ? $_REQUEST['quizID'] : 0;
        }

        $this->quizObject = new Quiz((int) $quizID);
        if (!$this->quizObject->quizID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => $this->quizObject
        ]);
    }
}
