<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObject;

class Game extends DatabaseObject
{
    // inherit vars
    protected static $databaseTableName = 'quiz_game';
    protected static $databaseTableIndexName = 'gameID';
}
