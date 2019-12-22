<?php

declare(strict_types=1);

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObject;

class Question extends DatabaseObject
{
    protected static $databaseTableName = 'quiz_question';
    protected static $databaseTableIndexName = 'questionID';
}
