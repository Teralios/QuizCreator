<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObjectList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\SystemException;

/**
 * Class        GameList
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class GameList extends DatabaseObjectList
{
    /**
     * @var bool
     */
    protected $withUser = false;

    /**
     * @var bool
     */
    protected $withQuiz = false;

    /**
     * @var int[]
     */
    protected $userIDs = [];

    /**
     * @var int[]
     */
    protected $quizIDs = [];

    /**
     * @param bool $withUser
     * @return $this
     */
    public function withUser(bool $withUser) //: static
    {
        $this->withUser = $withUser;

        return $this;
    }

    /**
     * @param bool $withQuiz
     * @return $this
     */
    public function withQuiz(bool $withQuiz) //: static
    {
        $this->withQuiz = $withQuiz;

        return $this;
    }

    public function readObjects()
    {
        parent::readObjects();

        if ($this->withQuiz || $this->withUser) {
            $this->prepareRead();

            if ($this->withUser) {
                $this->readUsers();
            }

            if ($this->withQuiz) {
                $this->readQuiz();
            }
        }
    }

    protected function prepareRead()
    {

    }

    protected function readUsers()
    {

    }

    protected function readQuiz()
    {

    }

    /**
     * @param Quiz|null $quiz
     * @return static
     */
    public static function bestPlayers(Quiz $quiz = null) //: static
    {
        $gameList = static::getBaseList($quiz);
        $gameList->sqlOrderBy = Game::getDatabaseTableAlias() . '.scorePercent DESC';
        $gameList->sqlOrderBy .= ', ' . Game::getDatabaseTableAlias() . ' .timeTotal ASC';

        return $gameList;
    }

    /**
     * @param Quiz|null $quiz
     * @return static
     */
    public static function lastPlayers(Quiz $quiz = null) //: static
    {
        $gameList = static::getBaseList($quiz);
        $gameList->sqlOrderBy = Game::getDatabaseTableAlias() . 'playedTime DESC';

        return $gameList;
    }

    /**
     * @param Quiz|null $quiz
     * @return static
     * @throws SystemException
     */
    protected static function getBaseList($quiz = null) //: static
    {
        $gameList = new static();

        if ($quiz !== null) {
            $gameList->getConditionBuilder()->add(
                Game::getDatabaseTableAlias() . '.quizID = ?',
                [$quiz->getObjectID()]
            );
        }

        return $gameList;
    }
}
