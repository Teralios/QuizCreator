<?php
namespace wcf\data\quiz\goal;

// imports
use wcf\data\DatabaseOBject;
use wcf\data\quiz\Quiz;
use wcf\system\WCF;

/**
 * Class goal
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 *
 * @property-read int $goalID
 * @property-read int $quizID
 * @property-read int $points
 * @property-read string $title
 * @property-read string $description
 */
class Goal extends DatabaseObject
{
    // inherit vars
    protected static $databaseTableName = 'quiz_goal';
    protected static $databaseTableIndexName = 'goalID';

    // point stages
    const POINTS_FUN = 1;
    const POINTS_COMPETITION_L1 = 10;
    const POINTS_COMPETITION_L2 = 5;
    const POINTS_COMPETITION_L3 = 1;
    const TIME_L1 = 5;
    const TIME_L2 = 15;

    public static function calculateMaxPoints(Quiz $quiz)
    {
        if ($quiz->type == Quiz::FUN) {
            return $quiz->questions * static::POINTS_FUN;
        }

        return $quiz->questions * static::POINTS_COMPETITION_L1;
    }

    public static function checkGoalPoints(int $quizID, int $points): bool
    {
        $sql = 'SELECT count(quizID) as goal
                FROM  ' . static::getDatabaseTableName() . '
                WHERE quizID = ?
                        AND points = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quizID, $points]);
        $row = $statement->fetchArray();

        return ($row['goal'] > 0) ? true : false;
    }
}
