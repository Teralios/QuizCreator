<?php
namespace wcf\data\quiz\goal;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

/**
 * Class GoalEditor
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 * @property-read int $goalID
 * @property-read int $quizID
 * @property-read int $points
 * @property-read string $title
 * @property-read string $description
 */
class GoalEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Goal::class;
}
