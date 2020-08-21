<?php

namespace wcf\data\quiz\game;

// imports
use wcf\data\DatabaseObjectEditor;

/**
 * Class        GameEditor
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz\game
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class GameEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Game::class;
}
