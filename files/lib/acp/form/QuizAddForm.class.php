<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\UploadFormField;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\language\LanguageFactory;
use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

/**
 * Class QuizAddForm
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuizAddForm extends AbstractFormBuilderForm
{
    public $objectActionClass = QuizAction::class;
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.add';
    public $neededPermissions = ['admin.content.quizMaker.canManage'];

    /**
     * @inheritDoc
     * @throws \wcf\system\exception\SystemException
     */
    public function createForm()
    {
        parent::createForm();

        $container = FormContainer::create('quizMakerGlobal');
        $container->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.global.title')
                ->maximumLength(80)
                ->required(),
            TextFormField::create('description')
                ->label('wcf.global.description')
                ->maximumLength(1000),
            SingleSelectionFormField::create('languageID')
                ->label('wcf.acp.quizMaker.quiz.language')
                ->options(LanguageFactory::getInstance()->getContentLanguages())
                ->available(LanguageFactory::getInstance()->multilingualismEnabled()),
            RadioButtonFormField::create('type')
                ->label('wcf.acp.quizMaker.quiz.type')
                ->options([
                    'fun' => 'wcf.acp.quizMaker.quiz.type.fun',
                    'competition' => 'wcf.acp.quizMaker.quiz.type.competition'
                ])
                ->value('fun'),
            UploadFormField::create('image')
                ->label('wcf.acp.quizMaker.quiz.image')
                ->imageOnly()
                ->maximum(1)
                ->maximumFilesize(2 * 1024 * 1024) // @todo set options
                /* @todo will be set when quiz design is finalized
                ->minimumImageHeight(128)
                ->maximumImageHeight(128)
                ->minimumImageWidth(512)
                ->maximumImageWidth(512)*/,
            BooleanFormField::create('isActive')
                ->label('wcf.acp.quizMaker.quiz.isActive')
                ->value(0)
                ->available(($this->formAction == 'edit') ? true : false)
        ]);

        $this->form->appendChild($container);
    }

    public function saved()
    {
        parent::saved();

        if ($this->formAction == 'create') {
            $quiz = $this->objectAction->getReturnValues()['returnValues'];
            if ($quiz instanceof Quiz) {
                HeaderUtil::redirect(LinkHandler::getInstance()->getLink('QuizEdit', ['id' => $quiz->quizID]));
            }
        }
    }
}
