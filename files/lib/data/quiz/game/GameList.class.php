<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObjectList;
use wcf\data\quiz\QuizList;
use wcf\data\user\UserProfileList;
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
     * @var UserProfileList|null
     */
    protected $userList = null;

    /**
     * @var QuizList|null
     */
    protected $quizList = null;

    /**
     * @return $this
     */
    public function withUser() //: static
    {
        $this->withUser = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withQuiz() //: static
    {
        $this->withQuiz = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
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

            $this->setAdditionalData();
        }
    }

    /**
     * Prepare read for quizzes and users.
     */
    protected function prepareRead()
    {
        /** @var Game $object */
        foreach ($this->objects as $object) {
            $this->userIDs[] = $object->userID;
            $this->quizIDs[] = $object->quizID;
        }
    }

    /**
     * Read users.
     */
    protected function readUsers()
    {
        $this->userList = new UserProfileList();
        $this->userList->setObjectIDs($this->userIDs);
        $this->userList->readObjects();
    }

    /**
     * Read quizzes.
     */
    protected function readQuiz()
    {
        $this->quizList = new QuizList();
        $this->quizList->setObjectIDs($this->quizIDs);
        $this->quizList->readObjects();
    }

    /**
     * Added additional data do game.
     */
    protected function setAdditionalData()
    {
        $users = ($this->userList !== null) ? $this->userList->getObjects() : [];
        $quizzes = ($this->quizList !== null) ? $this->quizList->getObjects() : [];

        /** @var Game $object */
        foreach ($this->objects as $object) {
            if (isset($users[$object->userID])) {
                /** @scrutinizer ignore-call */$object->setUser($users[$object->userID]);
            }

            if (isset($quizzes[$object->quizID])) {
                /** @scrutinizer ignore-call */$object->setQuiz($quizzes[$object->quizID]);
            }
        }
    }

    /**
     * @param int $quizID
     * @return static
     * @throws SystemException
     */
    public static function bestPlayers(int $quizID = 0) //: static
    {
        $gameList = static::getBaseList($quizID);
        $gameList->sqlOrderBy = Game::getDatabaseTableAlias() . '.scorePercent DESC';
        $gameList->sqlOrderBy .= ', ' . Game::getDatabaseTableAlias() . '.timeTotal ASC';

        return $gameList;
    }

    /**
     * @param int $quizID
     * @return static
     * @throws SystemException
     */
    public static function lastPlayers(int $quizID = 0) //: static
    {
        $gameList = static::getBaseList($quizID);
        $gameList->sqlOrderBy = Game::getDatabaseTableAlias() . '.playedTime DESC';

        return $gameList;
    }

    /**
     * @param int $quizID
     * @return static
     * @throws SystemException
     */
    protected static function getBaseList(int $quizID = 0) //: static
    {
        $gameList = new static();

        if ($quizID != 0) {
            $gameList->getConditionBuilder()->add(
                Game::getDatabaseTableAlias() . '.quizID = ?',
                [$quizID]
            );
        }

        return $gameList;
    }
}
