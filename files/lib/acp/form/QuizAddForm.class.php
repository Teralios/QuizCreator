<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\QuizAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\SystemException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\language\ContentLanguageFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\media\SingleMediaSelectionFormField;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

/**
 * Class QuizAddForm
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizAddForm extends AbstractFormBuilderForm
{
    // inherit vars
    public $objectActionClass = QuizAction::class;
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.add';
    public $neededPermissions = ['admin.content.quizMaker.canManage'];

    /**
     * @inheritDoc
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
            MultilineTextFormField::create('description')
                ->label('wcf.global.description')
                ->maximumLength(1000),
            ContentLanguageFormField::create('languageID'),
            RadioButtonFormField::create('type')
                ->label('wcf.acp.quizMaker.quiz.type')
                ->options([
                    'fun' => 'wcf.acp.quizMaker.quiz.type.fun',
                    'competition' => 'wcf.acp.quizMaker.quiz.type.competition'
                ])
                ->value('fun'),
            SingleMediaSelectionFormField::create('mediaID')
                ->imageOnly(),
            BooleanFormField::create('isActive')
                ->label('wcf.acp.quizMaker.quiz.isActive')
                ->value(0)
                ->available(($this->formAction == 'edit') ? true : false)
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
                HeaderUtil::redirect(LinkHandler::getInstance()->/** @scrutinizer ignore-call */getLink('QuizEdit', ['id' => $quiz->quizID, 'success' => 1]));
            }
        }
    }
}
