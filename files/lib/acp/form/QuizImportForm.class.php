<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\dependency\EmptyFormFieldDependency;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\language\LanguageFactory;
use wcf\util\JSON;

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

        // validators
        // @todo think about to make a own class for json data validation
        /**
         * Validates json data.
         * @param string $jsonString
         * @return string[]|null
         */
        $jsonValidator = function (string $jsonString) {
            // checks json syntax
            try {
                $jsonData = JSON::decode($jsonString);
            } catch (SystemException $e) {
                return ['json'];
            }

            // checks basic data
            $keys = array_keys($jsonData);
            foreach ($keys as $key) {
                if (!in_array($key, ['type', 'title', 'description', 'goals', 'questions'])) {
                    return ['data', $key];
                }
            }

            // checks language
            if (isset($jsonData['languageCode'])) {
                if (LanguageFactory::getInstance()->multilingualismEnabled()) {
                    $language = LanguageFactory::getInstance()->getLanguageByCode($jsonData['languageCode']);

                    if ($language === null) {
                        return ['language', $jsonData['languageCode']];
                    }
                }
            }

            // checks questions
            if (count($jsonData['questions'])) {
                $i = 0;
                foreach ($jsonData['questions'] as $question) {
                    $keys = array_keys($question);

                    foreach ($keys as $key) {
                        if (!in_array($key, ['position', 'question', 'optionA', 'optionB', 'optionC', 'optionD', 'answer'])) {
                            return ['question', $key, $i];
                        }
                    }

                    $i++;
                }
            }

            // checks goals
            if (count($jsonData['goals'])) {
                $i = 0;
                foreach ($jsonData['goals'] as $goal) {
                    $keys = array_keys($goal);

                    foreach ($keys as $key) {
                        if (!in_array($key, ['points', 'title', 'icon', 'description'])) {
                            return ['goals', $key, $i];
                        }
                    }

                    $i++;
                }
            }

            return null;
        };

        /**
         * Validates json file for import.
         * @param UploadFormField $formField
         */
        $fileValidator = function (UploadFormField $formField) use ($jsonValidator) {
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
                if ($jsonError[0] == 'json') {
                    $formField->addValidationError(new FormFieldValidationError('brokenJson', 'wcf.acp.quizCreator.import.error.json'));
                    return;
                } else {
                    $formField->addValidationError(new FormFieldValidationError('jsonData', 'wcf.acp.quizCreator.import.error.jsonData', $jsonError));
                }
            }
        };

        /**
         * Validate json text input.
         * @param MultilineTextFormField $formField
         */
        $textValidator = function (MultilineTextFormField $formField) use ($jsonValidator) {
            $jsonString = $formField->getSaveValue();

            // json test
            $jsonError = $jsonValidator($jsonString);

            if ($jsonError !== null) {
                if ($jsonError[0] == 'json') {
                    $formField->addValidationError(new FormFieldValidationError('brokenJson', 'wcf.acp.quizCreator.import.error.json'));
                    return;
                } else {
                    $formField->addValidationError(new FormFieldValidationError('jsonData', 'wcf.acp.quizCreator.import.error.jsonData', $jsonError));
                }
            }
        };

        // container
        $container = FormContainer::create('importQuiz');
        $container->appendChildren([
            UploadformField::create('file')
                ->label('wcf.acp.quizCreator.import.file')
                ->description('wcf.acp.quizCreator.import.file.description')
                ->maximum(1)
                /*->setAcceptableFiles('json') // comes with 5.3 */
                ->addValidator(new FormFieldValidator('quizFile', $fileValidator)),
            MultilineTextFormField::create('text')
                ->label('wcf.acp.quizCreator.import.text')
                ->description('wcf.acp.quizCreator.import.text.description')
                ->addValidator(new FormFieldValidator('quizText', $textValidator))
        ]);

        // dependency
        $dependency = EmptyFormFieldDependency::create('textOrFile');
        $dependency->field($container->getNodeById('text'));
        $container->getNodeById('file')->addDependency($dependency);

        $this->form->appendChild($container);
    }
}
