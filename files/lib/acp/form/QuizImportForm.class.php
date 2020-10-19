<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\dependency\NonEmptyFormFieldDependency;
use wcf\system\form\builder\field\dependency\ValueFormFieldDependency;
use wcf\system\form\builder\field\language\ContentLanguageFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\quiz\validator\Validator;
use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

/**
 * Class QuizImportForm
 *
 * @package    de.teralios.QuizCreator
 * @subpackage wcf\acp\form
 * @author     Karsten (Teralios) Achterrath
 * @copyright  ©2020 Teralios.de
 * @license    GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizImportForm extends AbstractFormBuilderForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.import';
    public $objectActionClass = QuizAction::class;
    public $objectActionName = 'import';

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        parent::createForm();

        // container
        $container = FormContainer::create('importQuiz');
        $container->appendChildren([
            RadioButtonFormField::create('type')
                ->label('wcf.acp.quizCreator.import.type')
                ->options([
                    'file' => 'wcf.acp.quizCreator.import.type.file',
                    'text' => 'wcf.acp.quizCreator.import.type.text'
                ]),
            UploadformField::create('file')
                ->label('wcf.acp.quizCreator.import.file')
                ->description('wcf.acp.quizCreator.import.file.description')
                ->maximum(1)
                ->setAcceptableFiles(['.json', '.quiz'])
                ->addValidator(new FormFieldValidator('quizFile', Validator::getUploadFieldValidator())),
            MultilineTextFormField::create('text')
                ->label('wcf.acp.quizCreator.import.text')
                ->description('wcf.acp.quizCreator.import.text.description')
                ->addValidator(new FormFieldValidator('quizText', Validator::getTextFieldValidator())),
            ContentLanguageFormField::create('languageID')
                ->required(),
            BooleanFormField::create('overrideLanguage')
                ->label('wcf.acp.quizCreator.import.overrideLanguage')
                ->description('wcf.acp.quizCreator.import.overrideLanguage.description'),
        ]);

        // dependency
        $dependency = ValueFormFieldDependency::create('useFile');
        $dependency->field($container->getNodeById('type'));
        $dependency->values(['file']);
        $container->getNodeById('file')->addDependency($dependency);

        $dependency = ValueFormFieldDependency::create('tuseText');
        $dependency->field($container->getNodeById('type'));
        $dependency->values(['text']);
        $container->getNodeById('text')->addDependency($dependency);

        $dependency = NonEmptyFormFieldDependency::create('languageOverride');
        $dependency->field($container->getNodeById('languageID'));
        $container->getNodeById('overrideLanguage')->addDependency($dependency);

        // add to form
        $this->form->appendChild($container);
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function saved()
    {
        $quiz = $this->objectAction->getReturnValues()['returnValues'];
        if ($quiz instanceof Quiz) {
            HeaderUtil::redirect(/** @scrutinizer ignore-call */LinkHandler::getInstance()->getLink(
                'QuizEdit',
                ['id' => $quiz->quizID, 'success' => 1]
            ));
        }
    }
}
