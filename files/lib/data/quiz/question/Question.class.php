<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\DatabaseObject;

class Question extends DatabaseObject
{
    protected static $databaseTableName = 'quiz_question';
    protected static $databaseTableIndexName = 'questionID';
}
