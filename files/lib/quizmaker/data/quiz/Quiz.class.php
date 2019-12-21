<?php

declare(strict_types=1);

namespace WCF\Quizmaker\Data\Quiz;

// imports
use WCF\Data\DatabaseObject;

/**
 * Class QuizData
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class Quiz extends DatabaseObject
{
    protected static $databaseTableName = 'quiz';
    protected static $databaseTableIndexName = 'quizID';
}
