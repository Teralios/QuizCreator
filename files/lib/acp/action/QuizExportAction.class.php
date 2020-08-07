<?php

namespace wcf\acp\action;

// imports
use wcf\action\AbstractAction;
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

class QuizExportAction extends AbstractAction
{
    public $quiz;
    public $quizID = 0;

    public function readParameters()
    {
       parent::readParameters();

        if (!WCF::getSession()->getPermission('admin.content.quizMaker.canManage')) {
            throw new PermissionDeniedException();
        }

        $this->quizID = $_REQUEST['id'] ?? 0;
        $this->quiz = new Quiz((int) $this->quizID);

        if (!$this->quiz->quizID) {
            throw new IllegalLinkException();
        }
    }

    public function execute()
    {
        // header
        @header('Content-type: application/json');
        @header('Content-disposition: attachment; filename="quiz-' . $this->quiz->quizID . '.json"');

        // no cache headers
        @header('Pragma: no-cache');
        @header('Expires: 0');

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}