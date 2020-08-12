<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObject;
use wcf\data\quiz\Quiz;
use wcf\system\WCF;

class Game extends DatabaseObject
{
    // inherit vars
    protected static $databaseTableName = 'quiz_game';
    protected static $databaseTableIndexName = 'gameID';

    public static function buildStatistic(Quiz $quiz): array
    {
        $sql = 'SELECT      COUNT(quizID) as players, SUM(score) as score, MAX(score) as best
                FROM        ' . static::getDatabaseTableName() . '
                WHERE       quizID = ?
                GROUP BY    quizID, score';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID]);
        $row = $statement->fetchSingleRow();

        // build statistic
        $statistic = [];
        $statistic['players'] = $row['players'] ?? 0;
        $statistic['score'] = $row['score'] ?? 0;
        $statistic['best'] = $row['best'] ?? 0;

        return $statistic;
    }
}
