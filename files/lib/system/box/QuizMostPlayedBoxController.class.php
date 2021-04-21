<?php

namespace wcf\system\box;

// imports
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\WCF;

class QuizMostPlayedBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    protected function loadContent()
    {
        $mostPlayed = /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->getData();

        if (count($mostPlayed)) {
            $this->content = WCF::getTPL()->fetch('__quizBoxMostPlayed', 'wcf', ['mostPlayed' => $mostPlayed]);
        }
    }
}
