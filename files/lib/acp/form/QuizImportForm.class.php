<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\util\JSON;

class QuizImportForm extends AbstractFormBuilderForm
{
    public $objectActionClass = QuizAction::class;
    public $objectActionName = 'import';

    public function createForm()
    {
        parent::createForm();

        // validator
        $fileValidator = new FormFieldValidator('quizFile', function (UploadFormField $formField) {
            $files = $formField->getValue();

            foreach ($files as $file) {
                $name = $file->getFilename();

                // file extension
                $lastDot = strrpos($name, '.');
                if ($lastDot !== false) {
                    $extension = substr($name, $lastDot + 1);

                    if ($extension !== 'json') {
                        $formField->addValidationError(new FormFieldValidationError('fileExtension', 'wcf.acp.quizMaker.import.error.file'));
                    }
                } else {
                    $formField->addValidationError(new FormFieldValidationError('unknownExtension', 'wcf.acp.quizMaker.import.error.unknown'));
                }

                // json
                try {
                    $jsonString = file_get_Contents($file->getLocation());
                    JSON::decode($jsonString);
                } catch (SystemException $e) {
                    $formField->addValidationError(new FormFieldValidationError('brokenJson', 'wcf.acp.quizMaker.import.error.json'));
                }
            }
        });

        // container
        $container = FormContainer::create('importQuiz');
        $container->appendChildren([
            UploadformField::create('file')
                ->required()
                ->maximum(1)
                ->setAcceptableFiles('json')
                ->addValidator($fileValidator)
        ]);
    }
}
