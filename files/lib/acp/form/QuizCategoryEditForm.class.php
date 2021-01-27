<?php

namespace wcf\acp\form;

use wcf\data\quiz\category\Category;
use wcf\data\quiz\category\QuizCategory;
use wcf\system\exception\IllegalLinkException;

/**
 * Class QuizCategoryEditForm
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryEditForm extends AbstractCategoryEditForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.category.list';

    /**
     * @inheritDoc
     */
    public $objectTypeName = QuizCategory::OBJECT_TYPE;

    /**
     * @inheritDoc
     */
    public $neededModules = ['MODULE_QUIZ_CREATOR'];
}
