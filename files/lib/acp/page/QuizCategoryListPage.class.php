<?php

namespace wcf\acp\page;

// imports
use wcf\data\quiz\category\QuizCategory;

/**
 * Class QuizCategoryListPage
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryListPage extends AbstractCategoryListPage
{
    public $objectTypeName = QuizCategory::OBJECT_TYPE;
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.category.list';
}
