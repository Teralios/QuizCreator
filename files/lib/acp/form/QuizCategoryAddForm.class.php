<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\category\QuizCategory;

/**
 * Class QuizCategoryAdd
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryAddForm extends AbstractCategoryAddForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.category.add';

    /**
     * @inheritDoc
     */
    public $objectTypeName = QuizCategory::OBJECT_TYPE;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_QUIZ_CREATOR'];
}
