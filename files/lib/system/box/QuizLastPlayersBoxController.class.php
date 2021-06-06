<?php

namespace wcf\system\box;

// imports

use wcf\page\QuizPage;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

class QuizLastPlayersBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    protected function loadContent()
    {
        $lastPlayers = null;

        if (
            /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest() !== null
            && /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizPage
        ) {
            $quiz = /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->quiz ?? null;
            if ($quiz !== null) {
                $lastPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                    'context' => 'last',
                    'quizID' => $quiz->quizID,
                    'withUser' => true,
                ]);
            }
        } else {
            $lastPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'last',
                'withQuiz' => true,
                'withUser' => true,
            ]);
        }

        if (is_array($lastPlayers) && count($lastPlayers)) {
            $this->content = WCF::getTPL()->fetch('__quizBoxLastPlayers', 'wcf', ['lastPlayers' => $lastPlayers]);
        }
    }
}
