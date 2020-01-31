<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\Quiz;
use wcf\data\quiz\question\QuestionAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\HiddenFormField;
use wcf\system\form\builder\field\RadioButtonFormField;
use wcf\system\form\builder\field\ShowOrderFormField;
use wcf\system\form\builder\field\TextFormField;

class QuestionAdd extends AbstractFormBuilderForm
{
    /**
     * @var Quiz
     */
    public $quizObject = null;
    public $formClassName = QuestionAction::class;

    public function readParameters()
    {
        parent::readParameters();

        $this->quizObject = (isset($_REQUEST['quizID'])) ? new Quiz(intval($_REQUEST['quizID'])) : null;
        if ($this->quizObject === null) {
            $this->quizObject = (isset($_REQUEST['id'])) ? new Quiz(intval($_REQUEST['id'])) : null;

            if ($this->quizObject === null || !$this->quizObject->quizID) {
                throw new IllegalLinkException();
            }
        }
    }

    public function buildForm()
    {
        parent::buildForm();
        $orderOptions = [];
        if ($this->quizObject->questions > 0) {
            for ($i = 1; $i <= $this->quizObject->questions; $i++) {
                $orderOptions[$i] = $i;
            }
        }

        $container = FormContainer::create('question');
        $container->appendChildren([
            HiddenFormField::create('quizID')
                ->value($this->quizObject->quizID),
            TextFormField::create('question')
                ->label('wcf.acp.quiz.question')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionA')
                ->label('wcf.acp.quiz.optionA')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionB')
                ->label('wcf.acp.quiz.optionB')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionC')
                ->label('wcf.acp.quiz.optionC')
                ->maximumLength(100)
                ->required(),
            TextFormField::create('optionD')
                ->label('wcf.acp.quiz.optionD')
                ->maximumLength(100)
                ->required(),
            RadioButtonFormField::create('answer')
                ->label('wcf.acp.quiz.answer')
                ->options([
                    'wcf.acp.quiz.optionA' => 'A',
                    'wcf.acp.quiz.optionB' => 'B',
                    'wcf.acp.quiz.optionC' => 'C',
                    'wcf.acp.quiz.optionD' => 'D'
                ]),
            ShowOrderFormField::create('orderNo')
                ->label('wcf.acp.quiz.orderNo')
                ->options($orderOptions),
        ]);

        $this->form->appendChild($container);
    }
}
