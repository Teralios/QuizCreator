<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObject;
use wcf\data\quiz\Quiz;
use wcf\data\user\UserProfile;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;
use wcf\util\JSON;
use wcf\util\StringUtil;

/**
 * Class        Match
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
     * @var UserProfile
     */
    protected $user;

    /**
     * @var Quiz
     */
    protected $quiz;

    /**
     * @var array
     */
    protected $questions;

    /**
     * Returns needed time for quiz as second.
     * @param boolean $last
     * @return string
     */
    public function getPlayTime(bool $last = false): string
    {
        $time = ($last) ? $this->lastTimeTotal : $this->timeTotal;
        $minutes = round($time / 60);
        $seconds = $time % 60;

        return $minutes . ':' . (($seconds < 10) ? '0' . $seconds : $seconds);
    }

    /**
     * Get result for question.
     * @param int $index
     * @return array|mixed
     * @throws SystemException
     */
    public function getQuestion(int $index = 0): array
    {
        if ($this->questions === null) {
            $this->parseResult();
        }

        return $this->questions[$index] ?? [];
    }

    /**
     * Sets user profile.
     * @param UserProfile $user
     */
    public function setUser(UserProfile $user): void
    {
        $this->user = $user;
    }

    /**
     * Returns user profile.
     * @return UserProfile|null
     */
    public function getUser(): ?UserProfile
    {
        return $this->user;
    }

    /**
     * Set quiz.
     * @param Quiz $quiz
     */
    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    /**
     * Return quiz.
     * @return Quiz|null
     */
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    /**
     * Parse official game result.
     * @throws SystemException
     */
    protected function parseResult(): void
    {
        $questions = JSON::decode($this->result);
        $i = 1;

        foreach ($questions as $question) {
            $this->questions[$i] = $question;
            $i++;
        }
    }

    /**
     * Builds statistic for game result.
     * @param Quiz $quiz
     * @param int $score
     * @return array
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
     */
    public static function getStatistic(Quiz $quiz, int $score = 0): array
    {
        $sql = 'SELECT      COUNT(userID) as players, SUM(score) as scoreTotal, MAX(score) as bestScore
                FROM        ' . static::getDatabaseTableName() . '
                WHERE       quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID]);
        $statistic = $statement->fetchSingleRow();

        if ($score > 0) {
            $worsePlayers = static::getWorsePlayers($quiz, $score);
        } else {
            $worsePlayers = 0;
        }

        if ($statistic['players'] > 0) {
            $betterAs = (float) $worsePlayers / $statistic['players'] * 100;
            $statistic['betterAs'] = ($betterAs > 0) ? StringUtil::formatDouble($betterAs) : '';
            $statistic['averageScore'] = StringUtil::formatDouble((float) $statistic['scoreTotal'] / $statistic['players']);
        }

        return $statistic;
    }

    /**
     * Get players with a lower score as player.
     * @param Quiz $quiz
     * @param int $score
     * @return int
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
     */
    public static function getWorsePlayers(Quiz $quiz, int $score): int
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
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
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
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
     */
    public static function getMatch(Quiz $quiz, int $userID): ?self
    {
        $sql = 'SELECT  *
                FROM    ' . static::getDatabaseTableName() . '
                WHERE   quizID = ?
                        AND userID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quiz->quizID, $userID]);

        return $statement->fetchObject(static::class);
    }
}
