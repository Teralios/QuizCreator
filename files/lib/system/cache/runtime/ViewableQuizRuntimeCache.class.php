<?php

namespace wcf\system\cache\runtime;

// imports
use wcf\data\quiz\ViewableQuizList;

class ViewableQuizRuntimeCache extends AbstractRuntimeCache
{
    protected $listClassName = ViewableQuizList::class;
}