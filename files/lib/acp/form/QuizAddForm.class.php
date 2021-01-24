<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\category\CategoryList;
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer;
use wcf\system\form\builder\data\processor\CustomFormDataProcessor;
use wcf\system\form\builder\field\DescriptionFormField;
use wcf\system\form\builder\field\language\ContentLanguageFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\tag\TagFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\media\SingleMediaSelectionFormField;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\wysiwyg\WysiwygFormField;
use wcf\system\form\builder\IFormDocument;
use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

/**
 * Class QuizAddForm
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizAddForm extends AbstractFormBuilderForm
{
    // inherit vars
    public $objectActionClass = QuizAction::class;
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.add';
    public $neededPermissions = ['admin.content.quizCreator.canManage'];

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        parent::createForm();

        // code 1.5.0 start
        $categoryOption = [];
        $categoryList = new CategoryList();
        $categoryList->defaultSorting()->readObjects();
        foreach ($categoryList as $category) {
            $categoryOption[$category->categoryID] = $category->name;
        }
        // code 1.5.0 end

        // description field
        if (QUIZ_DESCRIPTION_HTML) {
            $descriptionField = WysiwygFormField::create('description')
                ->label('wcf.global.description')
                ->objectType(Quiz::OBJECT_TYPE)
                ->maximumLength(QUIZ_DESCRIPTION_LENGTH)
                ->required()
                ->supportAttachments(false)
                ->supportMentions(false)
                ->supportQuotes(false);
        } else {
            $descriptionField = DescriptionFormField::create('description')
                ->maximumLength(QUIZ_DESCRIPTION_LENGTH)
                ->required();
        }

        $container = FormContainer::create('quizCreatorGlobal');
        $container->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.global.title')
                ->maximumLength(191)
                ->required(),
            // code 1.5.0 start
            SingleSelectionFormField::create('categoryID')
                ->label('wcf.acp.quizCreator.category')
                ->options($categoryOption),
            // code 1.5.0 end
            $descriptionField,
            TagFormField::create('tags')
                ->objectType(Quiz::OBJECT_TYPE),
            ContentLanguageFormField::create('languageID')
                ->required(),
            SingleMediaSelectionFormField::create('mediaID')
                ->label('wcf.acp.quizCreator.quiz.image')
                ->imageOnly(),
            BooleanFormField::create('isActive')
                ->label('wcf.acp.quizCreator.quiz.isActive')
                ->value(0)
                ->available($this->formAction == 'edit')
        ]);

        $this->form->appendChild($container);
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function saved()
    {
        parent::saved();

        if ($this->formAction == 'create') {
            $quiz = $this->objectAction->getReturnValues()['returnValues'];
            if ($quiz instanceof Quiz) {
                HeaderUtil::redirect(/** @scrutinizer ignore-call */LinkHandler::getInstance()->getLink(
                    'QuizEdit',
                    ['id' => $quiz->quizID, 'success' => 1]
                ));
            }
        }
    }
}
