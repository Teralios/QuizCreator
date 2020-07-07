<?php

namespace wcf\page;

// imports
use wcf\data\Quiz\Quiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Class QuizPage
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuizPage extends AbstractPage
{
    /**
     * @var Quiz
     */
    public $quiz;

    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     * @throws PermissionDeniedException
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->quizID = (int) $_REQUEST['id'];
        }

        $this->quiz = new Quiz($this->quizID);
        if (!$this->quiz->quizID) {
            throw new IllegalLinkException();
        }

        if ($this->quiz->isActive == 0 && !WCF::getSession()->getPermission('admin.content.quizMaker.canManage')) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => $this->quiz
        ]);
    }
}
