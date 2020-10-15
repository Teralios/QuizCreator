<?php

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\data\quiz\goal\GoalEditor;
use wcf\data\quiz\question\QuestionEditor;
use wcf\system\article\discussion\VoidArticleDiscussionProvider;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\language\LanguageFactory;
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
 * @method string getImage(bool $usePath)
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $type
 * @property-read string $image
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $goals
 * @property-read int $played
 */
class QuizEditor extends DatabaseObjectEditor implements IEditableCachedObject
{
    protected static $baseClass = Quiz::class;

    /**
     * Increment counters for quiz.
     *
     * @param bool $questions
     */
    public function incrementCounter(bool $questions = true)
    {
        if ($questions === true) {
            $data = ['questions' => 1];
        } else {
            $data = ['goals' => 1];
        }

        $this->updateCounters($data);
    }

    public function updatePlayed()
    {
        $this->updateCounters(['played' => 1]);
    }

    /**
     * Activate or deactivate a quiz.
     */
    public function toggle()
    {
        $this->update(['isActive' => ($this->isActive) ? 0 : 1]);
    }

    /**
     * Update counter for quiz after deletion of questions or stages.
     * @param int $quizID
     * @param int $counter
     * @param bool $questions
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public static function updateCounterAfterDelete(int $quizID, int $counter, bool $questions = true)
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
     * @param array $data
     * @return Quiz
     * @throws SystemException
     */
    public static function importQuiz(array $data): Quiz
    {
        // import base information for quiz
        $quizData = [];
        $quizData['type'] = $data['type'] ?? 'fun';
        $quizData['title'] = $data['title'] ?? WCF::getLanguage()->get('wcf.acp.quizCreator.import.defaultTitle');
        $quizData['description'] = $data['description'] ?? '';
        $quizData['creationDate'] = TIME_NOW;

        // html input processor
        $htmlProcessor = new HtmlInputProcessor();
        $htmlProcessor->process($quizData['description'], Quiz::OBJECT_TYPE);
        $quizData['description'] = $htmlProcessor->getHtml();

        // language information
        if (isset($data['languageCode'])) {
            if (/** @scrutinizer ignore-call */LanguageFactory::getInstance()->multilingualismEnabled()) {
                $language = /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getLanguageByCode($data['languageCode']);

                $quizData['languageID'] = ($language !== null) ? $language->languageID :
                    /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getContentLanguageIDs()[0];
            }
        }

        // create quiz
        $quiz = QuizEditor::create($quizData);

        $questions = static::importQuestions($data, $quiz->quizID);
        $goals = static::importGoals($data, $quiz->quizID);
        static::importTags($data, $quiz);

        // update counters
        $quizEditor = new QuizEditor($quiz);
        $quizEditor->update(['questions' => $questions, 'goals' => $goals]);

        return $quiz;
    }

    /**
     * Imports questions.
     * @param array $data
     * @param int $quizID
     * @return int
     * @throws DatabaseQueryException
     */
    protected static function importQuestions(array $data, int $quizID): int
    {
        $questions = 0;
        if (isset($data['questions']) && count($data['questions'])) {
            foreach ($data['questions'] as $question) {
                $question['quizID'] = $quizID;

                QuestionEditor::create($question);
                $questions++;
            }
        }

        return $questions;
    }

    /**
     * Import goals.
     * @param array $data
     * @param int $quizID
     * @return int
     */
    protected static function importGoals(array $data, int $quizID): int
    {
        $goals = 0;

        if (isset($data['goals']) && count($data['goals'])) {
            foreach ($data['goals'] as $goal) {
                $goal['quizID'] = $quizID;

                GoalEditor::create($goal);
                $goals++;
            }
        }

        return $goals;
    }

    /**
     * Import tags.
     * @param array $data
     * @param Quiz $quiz
     */
    protected static function importTags(array $data, Quiz $quiz)
    {
        if (isset($data['tags']) && count($data['tags'])) {
            /** @scrutinizer ignore-call */TagEngine::getInstance()->addObjectTags(
                Quiz::OBJECT_TYPE,
                $quiz->quizID,
                $data['tags'],
                $quiz->languageID ?? /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getDefaultLanguageID()
            );
        }
    }

    /**
     * @inheritdoc
     * @throws SystemException
     */
    public static function resetCache()
    {
        // reset general caches.
        /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->reset([
            'context' => 'best',
            'withQuiz' => true,
            'withUser' => true,
            'limit' => 10
        ]);

        /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->reset([
            'context' => 'last',
            'withQuiz' => true,
            'withUser' => true,
            'limit' => 10
        ]);

        /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->reset(['limit' => 10]);
    }
}
