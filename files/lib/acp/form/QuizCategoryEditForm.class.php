<?php

namespace wcf\acp\form;

use wcf\data\quiz\category\Category;
use wcf\system\exception\IllegalLinkException;

class QuizCategoryEditForm extends QuizCategoryAddForm
{
    // inherit vars
    public $formAction = 'edit';

    public function readParameters()
    {
        // read quiz
        $id = $_REQUEST['id'] ?? 0;
        $this->formObject = new Category((int) $id);
        if (!$this->formObject->categoryID) {
            throw new IllegalLinkException();
        }
    }
}
