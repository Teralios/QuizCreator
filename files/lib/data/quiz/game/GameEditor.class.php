<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\data\quiz\Quiz;
use wcf\data\user\User;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;
use wcf\util\JSON;

/**
 * Class        MatchEditor
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $userID
 * @property-read int $quizID
 * @property-read int $matchID
 * @property-read int $playedTime
 * @property-read int $timeTotal
 * @property-read int $score
 * @property-read float $scorePercent
 * @property-read int $lastScore
 * @property-read int $lastPlayedTime
 * @property-read int $lastTimeTotal
 * @property-read string $result
 */
class GameEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Game::class;

    /**
     * Returns data for update user.
     * @param User $user
     * @param bool $newResult
     * @return array
     */
    public function getUserData(User $user, bool $newResult = false): array
    {
        $userData = [];

        // new game
        if ($newResult) {
            $userData['quizPlayedUnique'] = $user->quizPlayedUnique + 1;
        }

        // all games
        $userData['quizPlayed'] = $user->quizPlayed + 1;

        // score
        $scoreNormalized = round($this->scorePercent * 10000, 0); // no float support, so 99,99% must be 9999 int. ;)
        $userData['quizMaxScore'] = ($user->quizMaxScore < $scoreNormalized) ? $scoreNormalized : $user->quizMaxScore;

        return $userData;
    }

    /**
     * Creates a new game result.
     *
     * @param Quiz $quiz
     * @param int $userID
     * @param int $score
     * @param int $time
     * @param array $result
     * @return static
     * @throws SystemException
     */
    public static function createGameResult(Quiz $quiz, int $userID, int $score, int $time, array $result): self
    {
        $scorePercent = round(($score / $quiz->getMaxScore()), 2);
        $data = [
            'userID' => $userID,
            'quizID' => $quiz->quizID,
            'score' => $score,
            'result' => JSON::encode($result),
            'scorePercent' => $scorePercent,
            'playedTime' => TIME_NOW,
            'timeTotal' => $time
        ];

        return new static(static::create($data));
    }

    /**
     * Delete all games/matches for given quiz.
     * @param int[] $quizIDs
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
     */
    public static function deleteForQuizzes(array $quizIDs)
    {
        if (count($quizIDs)) {
            $sql = 'DELETE FROM ' . Game::getDatabaseTAbleName() . '
                    WHERE quizID IN (?' . str_repeat(',?', count($quizIDs) - 1) . ')';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute($quizIDs);
        }
    }

    /**
     * @throws SystemException
     */
    public static function resetCache()
    {
        /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->reset();
    }
}
