<?php

namespace wcf\acp\form;

// imports
use wcf\data\quiz\goal\Goal;
use wcf\data\quiz\goal\GoalAction;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\DescriptionFormField;
use wcf\system\form\builder\field\HiddenFormField;
use wcf\system\form\builder\field\IconFormField;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\TitleFormField;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\form\builder\field\validation\FormFieldValidationError;

/**
 * Class QuizGoalAddForm
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizGoalAddForm extends BaseQuizForm
{
    // inherit vars
    public $activeMenuItem = 'wcf.acp.menu.link.quizCreator.list';
    public $objectActionClass = GoalAction::class;

    /**
     * @inheritDoc
     */
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
                    new FormFieldValidationError('invalid', 'wcf.acp.quizCreator.goal.points.exists')
                );
            }
        };

        $goalContainer = FormContainer::create('goal');
        $goalContainer->appendChildren([
            TitleFormField::create('title')
                ->label('wcf.global.title')
                ->maximumLength(150)
                ->required(),
            IntegerFormField::create('points')
                ->label('wcf.acp.quizCreator.goal.points')
                ->minimum(0)
                ->maximum(Goal::calculateMaxPoints($this->quizObject))
                ->addValidator(new FormFieldValidator('pointsExist', $pointsValidator))
                ->required(),
            IconFormField::create('icon')
                ->label('wcf.acp.quizCreator.goal.icon')
                ->required(),
            DescriptionFormField::create('description')
                ->maximumLength(1000),
            HiddenFormField::create('quizID')
                ->value($quizID)
        ]);

        $this->form->appendChild($goalContainer);
    }
}
