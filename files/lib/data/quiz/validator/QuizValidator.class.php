<?php

namespace wcf\data\quiz\validator;

// imports
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\util\JSON;

/**
 * Class QuizValidator
 *
 * @package    de.teralios.QuizCreator
 * @subpackage wcf\data\quiz
 * @author     Karsten (Teralios) Achterrath
 * @copyright  ©2020 Teralios.de
 * @license    GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizValidator {
    protected $neededBasicData = [];
    protected $allowedBasicData = [];
    protected $neededQuestionData = [];
    protected $allowedQuestionData = [];
    protected $neededGoalData = [];
    protected $allowedGoalData = [];
    protected $data = [];

    public function __construct()
    {
        $this->allowedBasicData = array_merge($this->allowedBasicData, $this->neededBasicData);
        $this->allowedQuestionData = array_merge($this->allowedQuestionData, $this->neededQuestionData);
        $this->allowedGoalData = array_merge($this->allowedGoalData, $this->neededGoalData);
    }

    public function setData(string $jsonString): bool {
        try {
            $this->data = JSON::decode($jsonString);
        } catch (SystemException $e) {
            return false;
        }

        return true;
    }

    public function validate(): QuizValidatorResult
    {

    }

    protected function checkBaseData(): QuizValidatorResult
    {

    }

    protected function checkQuestionData(): QuizValidatorResult
    {

    }

    protected function checkGoalData(): QuizValidatorResult
    {

    }
}
