<?php

namespace wcf\system\category;

use wcf\system\WCF;

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
