<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\DatabaseObject;

/**
 * Class Question
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class Question extends DatabaseObject
{
    /**
     * @var string
     */
    protected static $databaseTableName = 'quiz_question';

    /**
     * @var string
     */
    protected static $databaseTableIndexName = 'questionID';
}
