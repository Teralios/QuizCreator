<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\data\quiz\Quiz;
use wcf\data\user\User;
use wcf\system\exception\SystemException;
use wcf\util\JSON;

/**
 * Class        GameEditor
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class GameEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Game::class;

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
    public static function createGameResult(Quiz $quiz, int $userID, int $score, int $time, array $result)
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
     * Returns data for update user.
     * @param User $user
     * @param bool $newResult
     * @return array
     */
    public function getUserData(User $user, bool $newResult = false): array
    {
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
}
