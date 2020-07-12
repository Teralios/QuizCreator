<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\question\QuestionAction;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\HiddenFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\ShowOrderFormField;
use wcf\system\form\builder\field\TextFormField;

/**
 * Class QuestionAdd
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizQuestionAddForm extends BaseQuizForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizMaker.list';
    public $objectActionClass = QuestionAction::class;

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        parent::createForm();

        // prepare positions
        $orderOptions = [];
        $questions = $this->quizObject->questions + (($this->formAction == 'create' && $this->quizObject->questions > 0) ? 1 : 0);
        for ($i = 1; $i <= $questions; $i++) {
            $orderOptions[] = $i;
        }

        $container = FormContainer::create('questionForm');
        $container->appendChildren([
            HiddenFormField::create('quizID')
                ->value($this->quizObject->quizID),
            TextFormField::create('question')
                ->label('wcf.acp.quizMaker.question')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionA')
                ->label('wcf.acp.quizMaker.question.optionA')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionB')
                ->label('wcf.acp.quizMaker.question.optionB')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionC')
                ->label('wcf.acp.quizMaker.question.optionC')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionD')
                ->label('wcf.acp.quizMaker.question.optionD')
                ->maximumLength(100)
                ->required(),
            RadioButtonFormField::create('answer')
                ->label('wcf.acp.quizMaker.question.answer')
                ->options([
                    'A' => 'wcf.acp.quizMaker.question.optionA',
                    'B' => 'wcf.acp.quizMaker.question.optionB',
                    'C' => 'wcf.acp.quizMaker.question.optionC',
                    'D' => 'wcf.acp.quizMaker.question.optionD'
                ]),
            MultilineTextFormField::create('explanation')
                ->label('wcf.acp.quizMaker.question.explanation')
                ->maximumLength(500),
            ShowOrderFormField::create('position')
                ->label('wcf.acp.quizMaker.question.position')
                ->options($orderOptions)
        ]);

        $this->form->appendChild($container);
    }
}
