<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer;
use wcf\system\form\builder\field\DescriptionFormField;
use wcf\system\form\builder\field\language\ContentLanguageFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\tag\TagFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\media\SingleMediaSelectionFormField;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\wysiwyg\WysiwygFormField;
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

        $container = FormContainer::create('quizCreatorGlobal');
        $container->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.global.title')
                ->maximumLength(191)
                ->required(),
            WysiwygFormField::create('description')
                ->label('wcf.global.description')
                ->objectType(Quiz::OBJECT_TYPE)
                ->maximumLength(QUIZ_DESCRIPTION_LENGTH)
                ->required()
                ->supportAttachments(false)
                ->supportMentions(false)
                ->supportQuotes(false),
            TagFormField::create('tags')
                ->objectType(Quiz::OBJECT_TYPE),
            ContentLanguageFormField::create('languageID')
                ->required(),
            RadioButtonFormField::create('type')
                ->label('wcf.acp.quizCreator.quiz.type')
                ->description('wcf.acp.quizCreator.quiz.type.description')
                ->options([
                    'fun' => 'wcf.acp.quizCreator.quiz.type.fun',
                    'competition' => 'wcf.acp.quizCreator.quiz.type.competition'
                ])
                ->value('fun'),
            SingleMediaSelectionFormField::create('mediaID')
                ->label('wcf.acp.quizCreator.quiz.image')
                ->imageOnly(),
            BooleanFormField::create('isActive')
                ->label('wcf.acp.quizCreator.quiz.isActive')
                ->value(0)
                ->available($this->formAction == 'edit'),
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
