<?php

namespace wcf\system\category;

use wcf\system\WCF;

/**
 * Class QuizCategoryType
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\system\category
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since 1.5.0
 */
class QuizCategoryType extends AbstractCategoryType
{
    public $langVarPrefix = 'wcf.quizCreator.category';
    public $hasDescription = false;
    public $maximumNestingLevel = 0;

    /**
     * @inheritDoc
     */
    public function canAddCategory()
    {
        return WCF::getSession()->getPermission('admin.content.quizCreator.canManage');
    }

    /**
     * @inheritDoc
     */
    public function canDeleteCategory()
    {
        return $this->canAddCategory();
    }

    /**
     * @inheritDoc
     */
    public function canEditCategory()
    {
        return $this->canAddCategory();
    }
}
