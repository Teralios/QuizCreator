<?php

declare(strict_types=1);

namespace wcf\acp\form;

// imports
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\language\LanguageFactory;

class QuizAddForm extends AbstractFormBuilderForm
{
    public function createForm()
    {
        parent::createForm();

        $container = FormContainer::create('quizMakerGlobal');
        $container->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.quizMaker.form.title')
                ->maximumLength(80)
                ->required(),
            TextFormField::create('description')
                ->label('wcf.quizMaker.form.description')
                ->maximumLength(1000),
            SingleSelectionFormField::create('languageID')
                ->label('wcf.quizMaker.form.language')
                ->options(LanguageFactory::getInstance()->getContentLanguages())
                ->available(LanguageFactory::getInstance()->multilingualismEnabled()),
            RadioButtonFormField::create('type')
                ->label('wcf.quizMaker.form.type')
                ->options(
                    [
                        'fun' => 'wcf.quizMaker.form.type.fun',
                        'competition' => 'wcf.quizMaker.form.type.competition'
                    ]
                ),
            UploadFormField::create('image')
                ->label('wcf.quizMaker.form.image')
                ->imageOnly()
                ->allowSvgImage()
                ->maximumFilesize(2 * 1024 * 1024) // @todo set options
                ->maximumImageHeight(128) // @todo set options
                ->maximumImageWidth(512) // @todo set options
        ]);
    }
}
