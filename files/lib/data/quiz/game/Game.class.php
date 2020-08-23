<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObject;
use wcf\data\quiz\Quiz;
use wcf\data\user\UserProfile;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\WCF;

/**
 * Class        Game
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $userID
 * @property-read int $quizID
 * @property-read int $gameID
 * @property-read int $playedTime
 * @property-read int $timeTotal
 * @property-read int $score
 * @property-read float $scorePercent
 * @property-read int $lastScore
 * @property-read int $lastPlayedTime
 * @property-read int $lastTimeTotal
 * @property-read string $result
 */
class Game extends DatabaseObject
{
    // inherit vars
    protected static $databaseTableName = 'quiz_game';
    protected static $databaseTableIndexName = 'gameID';

    /**
     * @var UserProfile|null
     */
    protected $user = null;

    /**
     * @var Quiz|null
     */
    protected $quiz = null;

    /**
     * Sets user profile.
     * @param UserProfile $user
     */
    public function setUser(UserProfile $user)
    {
        $this->user = $user;
    }

    /**
     * Returns user profile.
     * @return UserProfile|null
     */
    public function getUser() //: ?UserProfile
    {
        return $this->user;
    }

    /**
     * Set quiz.
     * @param Quiz $quiz
     */
    public function setQuiz(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Return quiz.
     * @return Quiz|null
     */
    public function getQuiz() //: ?Quiz
    {
        return $this->quiz;
    }

    /**
     * Builds statistic for game result.
     * @param Quiz $quiz
     * @return array
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
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

    /**
     * Get players with a lower score as player.
     * @param Quiz $quiz
     * @param int $score
     * @return int
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
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

    /**
     * Check user has played the game.
     * @param Quiz $quiz
     * @param int $userID
     * @return bool
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
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

    /**
     * Returns game from player for quiz.
     * @param Quiz $quiz
     * @param int $userID
     * @return DatabaseObject|null
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public static function getGame(Quiz $quiz, int $userID)
    {
        $sql = 'SELECT  *
                FROM    ' . static::getDatabaseTableName() . '
                WHERE   quizID = ?
                        AND userID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID, $userID]);

        return $statement->fetchSingleObject(static::class);
    }
}
