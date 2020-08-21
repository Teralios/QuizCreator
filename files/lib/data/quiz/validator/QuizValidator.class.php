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
class QuizValidator
{
    /**
     * @var string[]
     */
    protected $requiredQuizData = ['type', 'title', 'questions', 'goals'];

    /**
     * @var string[]
     */
    protected $allowedQuizData = ['languageCode', 'description'];

    /**
     * @var string[]
     */
    protected $requiredQuestionData = ['question', 'optionA', 'optionB', 'optionC', 'optionD', 'answer'];

    /**
     * @var string[]
     */
    protected $allowedQuestionData = ['position', 'explanation'];

    /**
     * @var string[]
     */
    protected $requiredGoalData = ['points', 'title', 'icon'];

    /**
     * @var string[]
     */
    protected $allowedGoalData = ['description'];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * QuizValidator constructor.
     */
    public function __construct()
    {
        $this->allowedQuizData = array_merge($this->allowedQuizData, $this->requiredQuizData);
        $this->allowedQuestionData = array_merge($this->allowedQuestionData, $this->requiredQuestionData);
        $this->allowedGoalData = array_merge($this->allowedGoalData, $this->requiredGoalData);
    }

    /**
     * Set data.
     * @param string $jsonString
     * @return bool
     */
    public function setData(string $jsonString): bool
    {
        try {
            $this->data = JSON::decode($jsonString);
        } catch (SystemException $e) {
            return false;
        }

        return true;
    }

    /**
     * Validate data map for quiz import.
     * @return QuizValidatorError|null
     */
    public function validate()//: ?QuizValidatorResult
    {
        $functions = ['checkQuizData', 'checkQuestionData', 'checkGoalData'];

        foreach ($functions as $function) {
            $error = $this->{$function}();

            if ($error !== null) {
                return $error;
            }
        }

        return null;
    }

    /**
     * Checks quiz data.
     * @return QuizValidatorError|null
     * @throws SystemException
     */
    protected function checkQuizData()//: ?QuizValidatorError
    {
        // base check
        if (($requiredKey = $this->requiredData($this->data, $this->requiredQuizData)) !== null) {
            return QuizValidatorError::requiredKey('quiz', $requiredKey);
        }

        // allowed data
        if (($allowedKey = $this->allowedData($this->data, $this->allowedQuizData)) !== null) {
            return QuizValidatorError::notAllowedKey('quiz', $allowedKey);
        }

        // check language
        $languageCode = $this->data['languageCode'] ?? null;
        if ($languageCode !== null && /** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            $language = /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getLanguageByCode($languageCode);

            if ($language === null) {
                return QuizValidatorError::requiredKey('language', $languageCode);
            }
        }

        return null;
    }

    /**
     * Checks question data.
     * @return QuizValidatorError|null
     */
    protected function checkQuestionData()//: ?QuizValidatorError
    {
        return $this->checkDataMap('questions', $this->requiredQuestionData, $this->allowedQuestionData);
    }

    /**
     * Checks goal data.
     * @return QuizValidatorError|null
     */
    protected function checkGoalData()//: ?QuizValidatorError
    {
        return $this->checkDataMap('goals', $this->requiredGoalData, $this->allowedGoalData);
    }

    /**
     * Checks a complete data map.
     * @param string $checkKey
     * @param string[] $requiredKeys
     * @param string[] $allowedKeys
     * @return QuizValidatorError|null
     */
    protected function checkDataMap(string $checkKey, array $requiredKeys, array $allowedKeys) //: ?QuizValidatorError
    {
        $entries = $this->data[$checkKey];
        $i = 0;

        foreach ($entries as $entry) {
            if (($requiredKey = $this->requiredData($entry, $requiredKeys)) !== null) {
                return QuizValidatorError::requiredKey($checkKey, $requiredKey, $i);
            }

            if (($allowedKey = $this->allowedData($entry, $allowedKeys)) !== null) {
                return QuizValidatorError::notAllowedKey($checkKey, $allowedKey, $i);
            }

            $i++;
        }

        return null;
    }

    /**
     * Checks that a given data map has all required keys.
     * @param array $data
     * @param array $keys
     * @return string|null
     */
    protected function requiredData(array $data, array $keys) //: ?string
    {
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                return (string) $key;
            }
        }

        return null;
    }

    /**
     * Checks that a given data map has only allowed keys.
     * @param array $data
     * @param array $keys
     * @return string|null
     */
    protected function allowedData(array $data, array $keys) //: ?string
    {
        $dataKeys = array_keys($data);

        foreach ($dataKeys as $key) {
            if (!in_array($key, $keys)) {
                return (string) $key;
            }
        }

        return null;
    }
}
