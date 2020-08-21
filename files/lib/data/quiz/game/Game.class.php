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
        $sql = 'SELECT      COUNT(quizID) as players, SUM(score) as scoreSum, MAX(score) as best
                FROM        ' . static::getDatabaseTableName() . '
                WHERE       quizID = ?
                GROUP BY    quizID, score';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID]);
        $row = $statement->fetchSingleRow();

        // build statistic
        $statistic = [];
        $statistic['players'] = $row['players'] ?? 0;
        $statistic['scoreSum'] = $row['scoreSum'] ?? 0;
        $statistic['best'] = $row['best'] ?? 0;

        return $statistic;
    }

    public static function getPlayersWorse(Quiz $quiz, int $score): int
    {
        $sql = 'SELECT      COUNT(userID) as players
                FROM        ' . static::getDatabaseTableName() . '
                WHERE       quizID = ?
                            AND score < ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID, $score]);
        $row = $statement->fetchSingleRow();

        return (int) $row['players'];
    }

    public static function hasPlayed(Quiz $quiz, int $userID): bool
    {
        $sql = 'SELECT  COUNT(userID) as played
                FROM    ' . static::getDatabaseTAbleName() . '
                WHERE   quizID = ?
                        AND userID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID, $userID]);
        $row = $statement->fetchSingleRow();

        return ($row['played'] == 1);
    }
}
