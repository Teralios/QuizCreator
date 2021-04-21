<?php

namespace wcf\system\box;

use wcf\data\quiz\game\Game;
use wcf\page\QuizPage;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

class QuizUserResultBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    public function loadContent()
    {
        if (
            /** @scrutinizer ignore-call */ RequestHandler::getInstance()->getActiveRequest() !== null
            && /** @scrutinizer ignore-call */ RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizPage
            && WCF::getUser()->userID
        ) {
            $quiz = /** @scrutinizer ignore-call */
                RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->quiz ?? null;
            $game = Game::getMatch($quiz, WCF::getUser()->userID);

            $this->content = WCF::getTPL()->fetch('__quizBoxUserResult', 'wcf', ['game' => $game]);
        }
    }
}
