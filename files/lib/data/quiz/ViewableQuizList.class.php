<?php

namespace wcf\data\quiz;

// imports
use wcf\data\media\ViewableMediaList;
use wcf\data\quiz\match\Match;
use wcf\system\cache\runtime\ViewableMediaRuntimeCache;
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
     * ViewableQuizList constructor.
     * @throws SystemException
     */
    public function __construct()
    {
        parent::__construct();

        // permission setting
        if (!WCF::getSession()->getPermission('admin.content.quizCreator.canManage')) {
            $this->getConditionBuilder()->add($this->getDatabaseTableAlias() . '.isActive = ?', [1]);
        }
    }

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

        // read statistic for quiz
        if ($this->loadStatistic === true) {
            $this->loadStatistic();
        }
    }

    /**
     * Replace old way with a tempporary way.
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    protected function loadStatistic()
    {
        $sql = 'SELECT      COUNT(userID) as players, SUM(score) as score, quizID
                FROM        ' . Match::getDatabaseTableName() . '
                WHERE       quizID IN (? ' . str_repeat(', ?', (count($this->objectIDs) - 1)) . ')
                GROUP BY    quizID';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($this->objectIDs);

        while (($row = $statement->fetchArray()) !== false) {
            if (isset($this->objects[$row['quizID']])) {
                /** @var $quiz ViewableQuiz */
                $quiz = $this->objects[$row['quizID']];
                $quiz->setStatistic($row['score'], $row['players']);
            }
        }
    }

    /**
     * Read media.
     *
     * @param array $mediaIDs
     */
    protected function readMedia(array $mediaIDs)
    {
        /** @scrutinizer ignore-call */ViewableMediaRuntimeCache::getInstance()->cacheObjectIDs($mediaIDs);

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
                $quiz->setMedia(/** @scrutinizer ignore-call */ViewableMediaRuntimeCache::getInstance()->getObject($quiz->mediaID));
            }
        }
    }
}
