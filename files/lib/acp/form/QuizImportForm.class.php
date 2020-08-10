<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\QuizAction;
use wcf\data\quiz\validator\QuizValidator;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\dependency\EmptyFormFieldDependency;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;

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
                ->addValidator(new FormFieldValidator('quizText', $this->getTextValidator()))
        ]);

        // dependency
        $dependency = EmptyFormFieldDependency::create('textOrFile');
        $dependency->field($container->getNodeById('text'));
        $container->getNodeById('file')->addDependency($dependency);

        $this->form->appendChild($container);
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

            if (!empty($error->getKey())) {
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
            $jsonError = $jsonValidator(file_get_contents($file->getLocation()));

            if ($jsonError !== null) {
                $formField->addValidationError(new FormFieldValidationError('json', 'wcf.acp.quizCreator.import.error.json', $jsonError));
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

            // test json string
            $jsonError = $jsonValidator($jsonString);

            if ($jsonError !== null) {
                $formField->addValidationError(new FormFieldValidationError('json', 'wcf.acp.quizCreator.import.error.json', $jsonError));
            }
        };
    }
}
