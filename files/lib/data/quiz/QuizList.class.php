<?php
namespace wcf\data\quiz;

use wcf\data\DatabaseObjectList;

/**
 * Class QuizList
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuizList extends DatabaseObjectList
{
    public $className = Quiz::class;
}
