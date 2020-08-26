<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\data\quiz\validator\QuizValidator;
use wcf\data\quiz\validator\QuizValidatorError;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\dependency\EmptyFormFieldDependency;
use wcf\system\form\builder\field\dependency\NonEmptyFormFieldDependency;
use wcf\system\form\builder\field\language\ContentLanguageFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;
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
            UploadformField::create('file')
                ->label('wcf.acp.quizCreator.import.file')
                ->description('wcf.acp.quizCreator.import.file.description')
                ->maximum(1)
                /*->setAcceptableFiles('json') // comes with 5.3 */
                ->addValidator(new FormFieldValidator('quizFile', $this->getFileValidator())),
            MultilineTextFormField::create('text')
                ->label('wcf.acp.quizCreator.import.text')
                ->description('wcf.acp.quizCreator.import.text.description')
                ->addValidator(new FormFieldValidator('quizText', $this->getTextValidator())),
            ContentLanguageFormField::create('languageID')
                ->required(),
            BooleanFormField::create('overrideLanguage')
                ->label('wcf.acp.quizCreator.import.overrideLanguage')
                ->description('wcf.acp.quizCreator.import.overrideLanguage.description'),
        ]);

        // dependency
        $dependency = EmptyFormFieldDependency::create('textOrFile');
        $dependency->field($container->getNodeById('text'));
        $container->getNodeById('file')->addDependency($dependency);

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

    /**
     * Returns json validator.
     * @return callable
     */
    protected function getJsonValidator(): callable
    {
        return function (string $jsonString) {
            $validator = new QuizValidator();
            $validator->setData($jsonString);

            $error = $validator->validate();

            if ($error !== null) {
                return $error;
            }

            return null;
        };
    }

    /**
     * Returns file validator.
     * @return callable
     */
    public function getFileValidator(): callable
    {
        $jsonValidator = $this->getJsonValidator();

        return function (UploadFormField $formField) use ($jsonValidator) {
            $file = $formField->getValue()[0];

            $name = $file->getFilename();

            // file extension
            $lastDot = strrpos($name, '.');
            if ($lastDot !== false) {
                $extension = substr($name, $lastDot + 1);

                if ($extension !== 'json') {
                    $formField->addValidationError(new FormFieldValidationError('fileExtension', 'wcf.acp.quizCreator.import.error.file'));
                    return;
                }
            } else {
                $formField->addValidationError(new FormFieldValidationError('unknownExtension', 'wcf.acp.quizCreator.import.error.unknown'));
                return;
            }

            // json test
            /** @var QuizValidatorError $jsonError */
            $jsonError = $jsonValidator(file_get_contents($file->getLocation()));

            if ($jsonError !== null) {
                $information = [
                    'context' => $jsonError->getContext(),
                    'type' => $jsonError->getType(),
                    'key' => $jsonError->getKey(),
                    'index' => $jsonError->getIndex()
                ];
                $formField->addValidationError(new FormFieldValidationError('json', 'wcf.acp.quizCreator.import.error.json', $information));
            }
        };
    }

    /**
     * Returns text validator.
     * @return callable
     */
    protected function getTextValidator(): callable
    {
        $jsonValidator = $this->getJsonValidator();

        return function (MultilineTextFormField $formField) use ($jsonValidator) {
            $jsonString = $formField->getSaveValue();

            if (!empty($jsonString)) {
                // test json string
                $jsonError = $jsonValidator($jsonString);

                if ($jsonError !== null) {
                    $formField->addValidationError(new FormFieldValidationError('json', 'wcf.acp.quizCreator.import.error.json', [$jsonError]));
                }
            }
        };
    }
}
