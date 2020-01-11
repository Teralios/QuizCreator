<?php
namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObjectEditor;

/**
 * Class QuestionEditor
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuestionEditor extends DatabaseObjectEditor
{
    /**
     * @var string
     */
    protected static $baseClass = Question::class;
}
