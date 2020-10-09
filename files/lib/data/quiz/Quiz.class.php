<?php

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\data\ITitledLinkObject;
use wcf\data\media\ViewableMedia;
use wcf\system\Exception\SystemException;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\util\StringUtil;

/**
 * Class QuizData
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $type
 * @property-read int $mediaID
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $goals
 * @property-read int $played
 */
class Quiz extends DatabaseObject implements IRouteController, ITitledLinkObject
{
    // inherit vars
    protected static $databaseTableName = 'quiz';
    protected static $databaseTableIndexName = 'quizID';

    // const
    const MAX_VALUE_QUESTION = 10;
    const FUN_VALUE_QUESTION = 1;
    const TYPE_FUN = 'fun';
    const TYPE_COMPETITION = 'competition';
    const OBJECT_TYPE = 'de.teralios.quizCreator.quiz';

    /**
     * @var ViewableMedia
     */
    public $mediaObject = null;

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function getLink()
    {
        return /** @scrutinizer ignore-call */LinkHandler::getInstance()->getLink(
            'Quiz',
            [
                'object' => $this,
                'forceFrontend' => true
            ]
        );
    }

    /**
     * @return int
     */
    public function getMaxScore(): int
    {
        return static::calculateMaxScore($this);
    }

    /**
     * @param Quiz $quiz
     * @return int
     */
    public static function calculateMaxScore(Quiz $quiz): int
    {
        if ($quiz->type == Quiz::TYPE_FUN) {
            return $quiz->questions * static::FUN_VALUE_QUESTION;
        }

        return $quiz->questions * static::MAX_VALUE_QUESTION;
    }
}
