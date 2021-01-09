<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\DatabaseObjectEditor;

/**
 * Class CategoryEditor
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.1.0
 */
class CategoryEditor extends DatabaseObjectEditor
{
    // inherit variables
    protected static $baseClass = Category::class;
}
