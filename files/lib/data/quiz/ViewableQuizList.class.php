<?php

namespace wcf\data\quiz;

// imports
use wcf\data\media\ViewableMediaList;
use wcf\data\quiz\game\Game;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Class ViewableQuizList
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class ViewableQuizList extends QuizList
{
    // inherit vars
    public $decoratorClassName = ViewableQuiz::class;

    /**
     * @var bool
     */
    protected $loadMedia = false;

    /**
     * @var bool
     */
    protected $loadStatistic = false;

    /**
     * @var ViewableMediaList
     */
    protected $mediaList;

    /**
     * @var string[]
     */
    public $sqlGroupByFields = [
        'quizID',
        'languageID',
        'creationDate',
        'mediaID',
        'type',
        'title',
        'description',
        'isActive',
        'questions',
        'goals',
        'played'
    ];

    /**
     * @var string
     */
    public $sqlGroupBy = '';

    /**
     * Load media data for quizzes.
     */
    public function withMedia()
    {
        $this->loadMedia = true;
    }

    /**
     * Loads statistic for quizzes.
     */
    public function withStatistic()
    {
        $this->loadStatistic = true;
    }

    /**
     * @inheritDoc
     */
    public function readObjects()
    {
        /* if ($this->loadStatistic === true) {
            $this->buildStatisticSQL();
        } */

        parent::readObjects();

        // read media IDs.
        if ($this->loadMedia === true) {
            $mediaIDs = [];
            foreach ($this->objects as $quiz) {
                /** @var $quiz ViewableQuiz */
                if ($quiz->mediaID) {
                    $mediaIDs[] = $quiz->mediaID;
                }
            }

            if (count($mediaIDs) > 0) {
                $this->readMedia($mediaIDs);
            }
        }

        // read statistic for quiz ... only implement while WoltLab forgot GROUP BY support in DatabaseObjectList!
        if ($this->loadStatistic === true) {
            $this->loadStatisticTemp();
        }
    }

    /**
     * Replace old way with a tempporary way.
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    protected function loadStatisticTemp()
    {
        $sql = 'SELECT      COUNT(userID) as players, SUM(score) as score, quizID
                FROM        ' . Game::getDatabaseTableName() . '
                WHERE       quizID IN (? ' . str_repeat(', ?', (count($this->objectIDs) - 1)) . ')
                GROUP BY    quizID';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($this->objectIDs);

        while (($row = $statement->fetchArray()) != false) {
            if (isset($this->objects[$row['quizID']])) {
                /** @var $quiz ViewableQuiz */
                $quiz = $this->objects[$row['quizID']];
                $quiz->setStatistic($row['score'], $row['players']);
            }
        }
    }

    /**
     * Add default statistic sql parameters
     *//*
    protected function buildStatisticSQL()
    {
        $this->sqlSelects = 'COUNT(' . Game::getDatabaseTableAlias() . '.userID) AS players ';
        $this->sqlSelects .= ', SUM(' . Game::getDatabaseTableAlias() . '.score) AS score';

        $this->sqlJoins = 'LEFT JOIN ' . Game::getDatabaseTableName() . ' ' . Game::getDatabaseTableAlias() . ' ';
        $this->sqlJoins .= 'ON ' . $this->getDatabaseTableAlias() . '.quizID = ' . Game::getDatabaseTAbleAlias() . '.quizID ';

        // build group by
        if (count($this->sqlGroupByFields)) {
            $this->sqlGroupBy .= ' GROUP BY ' . implode(', ', $this->sqlGroupByFields);
        }
    }*/

    /**
     * Read media.
     *
     * @param array $mediaIDs
     */
    protected function readMedia(array $mediaIDs)
    {
        $this->mediaList = new ViewableMediaList();
        $this->mediaList->setObjectIDs($mediaIDs);
        $this->mediaList->readObjects();

        $this->setMedia();
    }

    /**
     * Set media to quiz.
     */
    protected function setMedia()
    {
        foreach ($this->objects as $quiz) {
            /** @var $quiz ViewableQuiz */
            if ($quiz->mediaID) {
                $quiz->setMedia($this->mediaList->search($quiz->mediaID));
            }
        }
    }
}
