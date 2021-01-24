<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\DatabaseObjectList;

/**
 * Class CategoryList
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class CategoryList extends DatabaseObjectList
{
    public $className = Category::class;

    public function defaultSorting(): self
    {
        $this->sqlOrderBy = $this->getDatabaseTableAlias() . '.position ASC';

        return $this;
    }
}
