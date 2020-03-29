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
use wcf\system\WCF;

/**
 * Class QuestionAdd
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 */
class QuestionAddForm extends AbstractFormBuilderForm
{
    // inherit vars
    public $objectActionClass = QuestionAction::class;

    /**
     * @var Quiz
     */
    public $quizObject = null;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     */
    public function readParameters()
    {
        parent::readParameters();

        $quizID = filter_input(INPUT_REQUEST, 'id', FILTER_VALIDATE_INT);
        if ($quizID === null || $quizID === false) {
            $quizID = filter_input(INPUT_REQUEST, 'quizID', FILTER_VALIDATE_INT);
        }

        $this->quizObject = ($quizID !== null && $quizID !== false) ? new Quiz($quizID) : null;
        if (!$this->quizObject->quizID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     */
    public function createForm()
    {
        parent::createForm();
        $orderOptions = [];
        $questions = $this->quizObject->questions + 1;
        for ($i = 1; $i <= $questions; $i++) {
            $orderOptions[$i] = $i;
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
            TextFormField::create('explanation')
                ->label('wcf.acp.quizMaker.question.explanation')
                ->maximumLength(500),
            ShowOrderFormField::create('position')
                ->label('wcf.acp.quizMaker.question.position')
                ->options($orderOptions)
        ]);

        $this->form->appendChild($container);
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'quiz' => $this->quizObject
        ]);
    }
}
