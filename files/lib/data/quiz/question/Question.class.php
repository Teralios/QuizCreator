<?php

namespace wcf\data\quiz\question;

// imports
use wcf\data\DatabaseObject;
use wcf\system\bbcode\SimpleMessageParser;
use wcf\system\exception\SystemException;

/**
 * Class Question
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $questionID
 * @property-read int $quizID
 * @property-read int $position
 * @property-read string $question
 * @property-read string $optionA
 * @property-read string $optionB
 * @property-read string $optionC
 * @property-read string $optionD
 * @property-read string $answer
 * @property-read string explanation
 */
class Question extends DatabaseObject
{
    protected static $databaseTableName = 'quiz_question';
    protected static $databaseTableIndexName = 'questionID';
    public const POSSIBLE_OPTIONS = ['A', 'B', 'C', 'D'];

    /**
     * Returns parsed explanation
     * @param bool $parsed
     * @return string
     * @throws SystemException
     */
    public function getExplanation(bool $parsed = true): string
    {
        return ($parsed) ? /** @scrutinizer ignore-call */SimpleMessageParser::getInstance()->parse($this->explanation) : $this->explanation;
    }

    /**
     * Returns text for given answer option.
     * @param string $option
     * @return string
     */
    public function getOption(string $option): string
    {
        $option = 'option' . mb_strtoupper($option);

        return $this->{$option};
    }

    /**
     * Returns possible options for answers.
     * @return string[]
     */
    public function getPossibleOptions(): array
    {
        return self::POSSIBLE_OPTIONS;
    }
}
