<?php

namespace wcf\data\quiz;

// imports
use wcf\data\media\ViewableMediaList;
use wcf\data\quiz\game\Game;
use wcf\system\exception\SystemException;

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
    protected $loadMedia = true;

    /**
     * @var bool
     */
    protected $loadStatistic = true;

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
        'goals'
    ];

    /**
     * ViewableQuizList constructor.
     * @param bool $loadMedia
     * @param bool $loadStatistic
     * @throws SystemException
     */
    public function __construct(bool $loadMedia = true, bool $loadStatistic = true)
    {
        parent::__construct();

        $this->loadMedia = $loadMedia;
        $this->loadStatistic = $loadStatistic;
    }

    /**
     * @param bool $loadMedia
     */
    public function loadMedia(bool $loadMedia)
    {
        $this->loadMedia = $loadMedia;
    }

    /**
     * @param bool $loadStatistic
     */
    public function loadStatistic(bool $loadStatistic)
    {
        $this->loadStatistic = $loadStatistic;
    }

    /**
     * @inheritDoc
     */
    public function readObjects()
    {
        // add sql commands for statistic
        if ($this->loadStatistic === true) {
            $this->buildStatisticSQL();
        }

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
    }

    /**
     * Add default statistic sql parameters
     */
    protected function buildStatisticSQL()
    {
        $this->sqlSelects = 'COUNT(' . Game::getDatabaseTableAlias() . '.userID) AS players ';
        $this->sqlSelects .= ', SUM(' . Game::getDatabaseTableAlias() . '.score) AS score';

        $this->sqlJoins = 'LEFT JOIN ' . Game::getDatabaseTableName() . ' ' . Game::getDatabaseTableAlias() . ' ';
        $this->sqlJoins .= 'ON ' . $this->getDatabaseTableAlias() . '.quizID = ' . Game::getDatabaseTAbleAlias() . '.quizID ';

        // build group by
        if (count($this->sqlGroupByFields)) {
            $this->sqlJoins .= ' GROUP BY ' . implode(', ', $this->sqlGroupByFields);
        }
    }

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
