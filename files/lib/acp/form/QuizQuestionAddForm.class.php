<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\question\QuestionAction;
use wcf\data\quiz\Quiz;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\HiddenFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\ShowOrderFormField;
use wcf\system\form\builder\field\TextFormField;

/**
 * Class QuestionAdd
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizQuestionAddForm extends BaseQuizForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.list';
    public $objectActionClass = QuestionAction::class;

    /**
     * @inheritDoc
     */
    public function createForm(): void
    {
        parent::createForm();

        $container = FormContainer::create('questionForm');
        $container->appendChildren([
            HiddenFormField::create('quizID')
                ->value($this->quizObject->quizID),
            TextFormField::create('question')
                ->label('wcf.acp.quizCreator.question')
                ->maximumLength(150)
                ->required(),
            TextFormField::create('optionA')
                ->label('wcf.acp.quizCreator.question.optionA')
                ->maximumLength(150)
                ->required(),
            TextFormField::create('optionB')
                ->label('wcf.acp.quizCreator.question.optionB')
                ->maximumLength(150)
                ->required(),
            TextFormField::create('optionC')
                ->label('wcf.acp.quizCreator.question.optionC')
                ->maximumLength(150)
                ->required(),
            TextFormField::create('optionD')
                ->label('wcf.acp.quizCreator.question.optionD')
                ->maximumLength(150)
                ->required(),
            RadioButtonFormField::create('answer')
                ->label('wcf.acp.quizCreator.question.answer')
                ->options([
                    'A' => 'wcf.acp.quizCreator.question.optionA',
                    'B' => 'wcf.acp.quizCreator.question.optionB',
                    'C' => 'wcf.acp.quizCreator.question.optionC',
                    'D' => 'wcf.acp.quizCreator.question.optionD'
                ]),
            MultilineTextFormField::create('explanation')
                ->label('wcf.acp.quizCreator.question.explanation')
                ->maximumLength(1000),
            ShowOrderFormField::create('position')
                ->label('wcf.acp.quizCreator.question.position')
        ]);

        $this->form->appendChild($container);

        // set order options
        $orderOptions = [];

        // reload quiz object
        $this->quizObject = new Quiz($this->quizObject->quizID);
        $showOrderValue = $this->quizObject->questions;
        $maxShowOrder = $showOrderValue + (($this->formAction === 'create' && $this->quizObject->questions > 0) ? 1 : 0);

        for ($i = 2; $i <= $maxShowOrder; $i++) { // start here with 2.
            $orderOptions[$i - 1] = $i;
        }

        /** @var ShowOrderFormField $showOrderField */
        $showOrderField = $this->form->getNodeById('position');
        $showOrderField->options($orderOptions)->value($showOrderValue);
    }
}
