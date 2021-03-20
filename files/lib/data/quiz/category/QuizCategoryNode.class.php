<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\category\CategoryNode;
use wcf\system\request\LinkHandler;

class QuizCategoryNode extends CategoryNode
{
    public function getLink(): string
    {
        return /** @scrutinizer ignore-call */LinkHandler::getInstance()->getLink('QuizList', ['object' => $this]);
    }
}
