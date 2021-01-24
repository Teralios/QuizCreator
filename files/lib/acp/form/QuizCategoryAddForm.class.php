<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\category\CategoryAction;
use wcf\data\quiz\category\CategoryList;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\event\EventHandler;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\SortOrderFormField;
use wcf\system\form\builder\field\TitleFormField;

/**
 * Class QuizCategoryAdd
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.1.0
 */
class QuizCategoryAddForm extends AbstractFormBuilderForm
{
    // inherit variables
    public $objectActionClass = CategoryAction::class;
    public $objectEditLinkController = QuizCategoryEditForm::class;

    /**
     * @var CategoryList
     */
    public $categoryList;

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        parent::createForm();

        // order option
        $maxSort = $this->categoryList->countObjects();
        $orderOptions = [];
        for ($i = 2; $i <= $maxSort; $i++) {
            $orderOptions[$i - 1] = $i;
        }

        // field
        $categoryFormContainer = FormContainer::create('category');
        $categoryFormContainer->appendChildren([
            TitleFormField::create('name')
                ->i18nRequired(),
            SortOrderFormField::create('position')
                ->options($orderOptions)
        ]);

        $this->form->appendChild($categoryFormContainer);
    }

    public function loadFormFieldData()
    {
        EventHandler::getInstance()->fireAction($this, 'loadFormFieldData');

        $this->categoryList = new CategoryList();
        $this->categoryList->sqlOrderBy = $this->categoryList->getDatabaseTableAlias() . '.position ASC';
    }
}
