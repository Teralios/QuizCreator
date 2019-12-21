<?php

declare(strict_types=1);

namespace WCF\Quizmaker\Data\Quiz;

// imports
use WCF\Data\DatabaseObject;

class Question extends DatabaseObject
{
    protected static $databaseTableName = 'quiz_question';
    protected static $databaseTableIndexName = 'questionID';
}
