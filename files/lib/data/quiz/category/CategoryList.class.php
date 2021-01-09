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
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 * @since     1.1.0
 */
class CategoryList extends DatabaseObjectList
{
    public $className = Category::class;
}
