<?php

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\data\quiz\goal\GoalEditor;
use wcf\data\quiz\question\QuestionEditor;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\language\LanguageFactory;
use wcf\system\validator\data\quiz\Goal as ValidatedGoal;
use wcf\system\validator\data\quiz\Question as ValidatedQuestion;
use wcf\system\validator\data\quiz\Quiz as ValidatedQuiz;
use wcf\system\validator\data\quiz\Tag as ValidatedTag;
use wcf\system\tagging\TagEngine;
use wcf\system\WCF;

/**
 * Class QuizEditor
 *
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $image
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $goals
 * @property-read int $played
 */
class QuizEditor extends DatabaseObjectEditor implements IEditableCachedObject
{
    // inherit variables
    protected static $baseClass = Quiz::class;

    /**
     * Increment counters for quiz.
     *
     * @param bool $questions
     * @return void
     */
    public function incrementCounter(bool $questions = true): void
    {
        if ($questions === true) {
            $data = ['questions' => 1];
        } else {
            $data = ['goals' => 1];
        }

        $this->updateCounters($data);
    }

    /**
     * Update played counter.
     * @return void
     */
    public function updatePlayed(): void
    {
        $this->updateCounters(['played' => 1]);
    }

    /**
     * Activate or deactivate a quiz.
     * @return void
     */
    public function toggle(): void
    {
        $this->update(['isActive' => ($this->isActive) ? 0 : 1]);
    }

    /**
     * Import quiz data
     * @param ValidatedQuiz $data
     * @return void
     * @throws DatabaseQueryException|SystemException
     */
    public function importData(ValidatedQuiz $data): void
    {
        // import questions, goals and tags.
        $questions = ($data->has('questions')) ? $this->importQuestions($data->questions) : 0;
        $goals = ($data->has('goals')) ? $this->importGoals($data->goals) : 0;
        if ($data->has('tags')) {
            $this->importTags($data->tags);
        }

        $this->update(['questions' => $questions, 'goals' => $goals]);
    }

    /**
     * Imports questions.
     * @param ValidatedQuestion[] $questions
     * @return int
     * @throws DatabaseQueryException
     */
    protected function importQuestions(array $questions): int
    {
        $numbers = 0;
        foreach ($questions as $question) {
            $data = $question->getData();
            $data['quizID'] = $this->quizID;
            QuestionEditor::create($data);

            $numbers++;
        }

        return $numbers;
    }

    /**
     * Import goals.
     * @param ValidatedGoal[] $goals
     * @return int
     */
    protected function importGoals(array $goals): int
    {
        $numbers = 0;
        foreach ($goals as $goal) {
            $data = $goal->getData();
            $data['quizID'] = $this->quizID;
            GoalEditor::create($data);

            $numbers++;
        }

        return $numbers;
    }

    /**
     * Import tags.
     * @param ValidatedTag[] $tags
     * @return void
     * @throws SystemException
     */
    protected function importTags(array $tags): void
    {
        $data = [];
        foreach ($tags as $tag) {
            $data[] = $tag->name;
        }

        if (count($data)) {
            /** @scrutinizer ignore-call */TagEngine::getInstance()->addObjectTags(
                Quiz::OBJECT_TYPE,
                $this->quizID,
                $data,
                $this->languageID ?? /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getDefaultLanguageID()
            );
        }
    }


    /**
     * Update counter for quiz after deletion of questions or stages.
     * @param int $quizID
     * @param int $counter
     * @param bool $questions
     * @return void
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public static function updateCounterAfterDelete(int $quizID, int $counter, bool $questions = true): void
    {
        $field = ($questions === true) ? 'questions' : 'goals';
        $sql = 'UPDATE  ' . static::getDatabaseTAbleNAme() . '
                SET     ' . $field . ' = ' . $field . ' - ?
                WHERE   quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$counter, $quizID]);
    }

    /**
     * Imports a quiz.
     * @param ValidatedQuiz $data
     * @param int $languageID
     * @param bool $overrideLanguage
     * @return Quiz
     * @throws SystemException
     */
    public static function importQuiz(ValidatedQuiz $data, int $languageID, bool $overrideLanguage = false): Quiz
    {
        // import base information for quiz
        $quizData = [];
        $quizData['type'] = $data->type;
        $quizData['title'] = $data->title;
        $quizData['description'] = $data->description;
        $quizData['creationDate'] = TIME_NOW;

        // html input processor
        $htmlProcessor = new HtmlInputProcessor();
        $htmlProcessor->process($quizData['description'], Quiz::OBJECT_TYPE);
        $quizData['description'] = $htmlProcessor->getHtml();

        // language information
        if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
            if ($data->has('languageCode') && !$overrideLanguage) {
                $language = /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getLanguageByCode($data->languageCode);
                $quizData['languageID'] = ($language !== null) ? $language->languageID : $languageID;
            } else {
                $quizData['languageID'] = $languageID;
            }
        }

        // create quiz
        $quiz = QuizEditor::create($quizData);

        // update counters
        $quizEditor = new QuizEditor($quiz);
        $quizEditor->importData($data);

        return $quiz;
    }

    /**
     * @inheritdoc
     * @return void
     * @throws SystemException
     */
    public static function resetCache(): void
    {
        // reset general caches.
        /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->reset([
            'context' => 'best',
            'withQuiz' => true,
            'withUser' => true
        ]);

        /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->reset([
            'context' => 'last',
            'withQuiz' => true,
            'withUser' => true
        ]);

        /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->reset();
    }
}
