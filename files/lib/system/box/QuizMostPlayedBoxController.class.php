<?php

namespace wcf\system\box;

class QuizMostPlayedBoxController extends AbstractBoxController
{
    protected function loadContent()
    {
        $mostPlayed = /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->getData();
    }
}
