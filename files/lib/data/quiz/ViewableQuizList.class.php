<?php

namespace wcf\data\quiz;

use wcf\data\media\ViewableMediaList;
use wcf\system\exception\SystemException;

/**
 * Class ViewableQuizList
 *
 * @package   de.teralios.quizMaker
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
     * @var ViewableMediaList
     */
    protected $mediaList;

    /**
     * ViewableQuizList constructor.
     * @param bool $loadMedia
     * @throws SystemException
     */
    public function __construct(bool $loadMedia)
    {
        parent::__construct();

        $this->loadMedia = $loadMedia;
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
