<?php

namespace wcf\action;

// imports
use wcf\data\quiz\AJAXQuiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;

class GetQuizAction extends AbstractAjaxAction
{
    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @var AJAXQuiz
     */
    public $quiz;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     */
    public function readParameters()
    {
        parent::readParameters();

        $this->quizID = (isset($_REQUEST['id'])) ? (int) $_REQUEST['id'] : 0;
        $this->quiz = new AJAXQuiz($this->quizID);

        if (!$this->quiz->quizID && !$this->quiz->isActive) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     * @throws PermissionDeniedException
     */
    public function execute()
    {
        parent::execute();

        $this->sendJsonResponse($this->quiz->getData());
    }
}