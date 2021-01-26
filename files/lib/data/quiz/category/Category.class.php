<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\DatabaseObject;

/**
 * Class Category
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 *
 * @property-read int $categoryID
 * @property-read int $position;
 * @property-read string $name;
 */
class Category extends DatabaseObject
{
    // inherit variables
    public static $databaseTableName = 'quiz_category';
    public static $databaseTableIndexName = 'categoryID';
    public const LANGUAGE_CATEGORY = 'wcf.quizCreator.category';

    public static function getLanguageItem(Category $category): string
    {
        return static::LANGUAGE_CATEGORY . '.category' . (string) $category->getObjectID();
    }
}
