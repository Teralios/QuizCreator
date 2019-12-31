<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\QuizAction;
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
    public $objectActionClass = QuizAction::class;
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.add';

    public function createForm()
    {
        parent::createForm();

        $container = FormContainer::create('quizMakerGlobal');
        $container->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.acp.quizMaker.form.title')
                ->maximumLength(80)
                ->required(),
            TextFormField::create('description')
                ->label('wcf.acp.quizMaker.form.description')
                ->maximumLength(1000),
            SingleSelectionFormField::create('languageID')
                ->label('wcf.acp.quizMaker.form.language')
                ->options(LanguageFactory::getInstance()->getContentLanguages())
                ->available(LanguageFactory::getInstance()->multilingualismEnabled()),
            RadioButtonFormField::create('type')
                ->label('wcf.acp.quizMaker.form.type')
                ->options(
                    [
                        'fun' => 'wcf.acp.quizMaker.form.type.fun',
                        'competition' => 'wcf.acp.quizMaker.form.type.competition'
                    ]
                ),
            UploadFormField::create('image')
                ->label('wcf.acp.quizMaker.form.image')
                ->imageOnly()
                ->maximum(1)
                ->maximumFilesize(2 * 1024 * 1024) // @todo set options
                /* @todo will be set when quiz design is finalized
                ->minimumImageHeight(128)
                ->maximumImageHeight(128)
                ->minimumImageWidth(512)
                ->maximumImageWidth(512)*/
        ]);

        $this->form->appendChild($container);
    }
}
