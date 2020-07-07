<?php
namespace wcf\data\quiz\goal;

// imports
use wcf\data\DatabaseObjectEditor;

/**
 * Class GoalEditor
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
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
