<?php

namespace wcf\system\box;

// imports
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\WCF;

class QuizMostPlayedBoxController extends AbstractBoxController
{
    protected function loadContent()
    {
        $mostPlayed = /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->getData();

        $this->content = WCF::getTPL()->fetch('__quizBoxMostPlayed.tpl', 'WCF', ['mostPlayed' => $mostPlayed]);
    }
}
