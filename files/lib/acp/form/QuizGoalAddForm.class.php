<?php
namespace wcf\acp\form;

// imports
use wcf\data\quiz\goal\Goal;
use wcf\data\quiz\goal\GoalAction;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\HiddenFormField;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\form\builder\field\validation\FormFieldValidationError;

class QuizGoalAddForm extends BaseQuizForm
{
    // inherit vars
    public $objectActionClass = GoalAction::class;

    public function createForm()
    {
        parent::createForm();
        $quizID = $this->quizObject->quizID;
        $formObject = $this->formObject;

        // points validator
        $pointsValidator = function (IntegerFormField $field) use ($quizID, $formObject) {
            $data = $field->getSaveValue();

            if ($formObject instanceof Goal && $formObject->points == $data) {
                return;
            }

            if (Goal::checkGoalPoints($quizID, $data)) {
                $field->addValidationError(
                    new FormFieldValidationError('invalid', 'wcf.acp.quizMaker.goal.points.exists')
                );
            }
        };

        $goalContainer = FormContainer::create('goal');
        $goalContainer->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.global.title')
                ->maximumLength(255)
                ->required(),
            IntegerFormField::create('points')
                ->label('wcf.acp.quizMaker.goal.points')
                ->minimum(0)
                ->maximum(Goal::calculateMaxPoints($this->quizObject))
                ->addValidator(new FormFieldValidator('pointsExist', $pointsValidator))
                ->required(),
            TextFormField::create('description')
                ->label('wcf.acp.quizMaker.goal.description')
                ->maximumLength(500),
            HiddenFormField::create('quizID')
                ->value($quizID)
        ]);

        $this->form->appendChild($goalContainer);
    }
}
