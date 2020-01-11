<?php
namespace wcf\data\quiz;

use wcf\data\DatabaseObjectList;
use wcf\data\quiz\question\Question;
use wcf\system\WCF;

class QuizList extends DatabaseObjectList
{
    /**
     * @var string
     */
    public $className = Quiz::class;
}
