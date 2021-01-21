<?php

namespace wcf\data\quiz;

// imports
use wcf\data\media\ViewableMediaList;
use wcf\data\quiz\game\Game;
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
     * @param ?int $categoryID
     */
    public function __construct(?int $categoryID = null)
    {
        parent::__construct();

        // permission setting
        if (!WCF::getSession()->getPermission('admin.content.quizCreator.canManage')) {
            $this->getConditionBuilder()->add(
                $this->getDatabaseTableAlias() . '.isActive = ?',
                [1]
            );
        }

        // 1.5 code start
        if ($categoryID === null || $categoryID == 0) {
            $this->getConditionBuilder()->add(
                $this->getDatabaseTableAlias() . '.categoryID = ?',
                [$categoryID]
            );
        }
        // 1.5 code end
    }

    /**
     * Load media data for quizzes.
     * @return self
     */
    public function withMedia(): self
    {
        $this->loadMedia = true;
        return $this;
    }

    /**
     * Loads statistic for quizzes.
     * @return self
     */
    public function withStatistic(): self
    {
        $this->loadStatistic = true;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws DatabaseQueryExecutionException|SystemException
     */
    public function readObjects(): self
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
     * Replace old way with a temporary way.
     * @throws DatabaseQueryException|DatabaseQueryExecutionException
     */
    protected function loadStatistic()
    {
        $sql = 'SELECT      COUNT(userID) as players, SUM(score) as score, quizID
                FROM        ' . Game::getDatabaseTableName() . '
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
     * @param array $mediaIDs
     * @throws SystemException
     */
    protected function readMedia(array $mediaIDs)
    {
        /** @scrutinizer ignore-call */ViewableMediaRuntimeCache::getInstance()->cacheObjectIDs($mediaIDs);

        $this->setMedia();
    }

    /**
     * Set media to quiz.
     * @throws SystemException
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
