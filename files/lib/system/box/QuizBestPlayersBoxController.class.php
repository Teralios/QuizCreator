<?php

namespace wcf\system\box;

// imports

use wcf\page\QuizPage;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

class QuizBestPlayersBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    protected function loadContent()
    {
        $bestPlayers = null;

        if (
            /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest() !== null
            && /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizPage
        ) {
            $quiz = /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->quiz ?? null;
            if ($quiz !== null) {
                $bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                    'context' => 'best',
                    'quizID' => $this->quiz->quizID,
                    'withUser' => true,
                ]);
            }
        } else {
            $bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'best',
                'withQuiz' => true,
                'withUser' => true,
            ]);
        }

        $content = WCF::getTPL()->fetch('__quizBoxBestPlayers', 'wcf', ['bestPlayers' => $bestPlayers]);
    }
}
