<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\IStorableObject;
use wcf\data\IToggleAction;
use wcf\data\quiz\goal\Goal;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionEditor;
use wcf\data\quiz\question\QuestionList;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\JSON;

/**
 * Class QuizAction
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizAction extends AbstractDatabaseObjectAction implements IToggleAction
{
    // inherit vars
    protected $className = QuizEditor::class;
    protected $permissionsCreate = ['admin.content.quizMaker.canManage'];
    protected $permissionsUpdate = ['admin.content.quizMaker.canManage'];
    protected $permissionsDelete = ['admin.content.quizMaker.canManage'];
    protected $permissionsToggle = ['admin.content.quizMaker.canManage'];
    protected $allowGuestAccess = ['loadQuiz']; // allowed guest access

    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * @inheritDoc
     */
    public function create()
    {
        // set timestamp
        $this->parameters['data']['creationDate'] = TIME_NOW;

        // create database entry
        return parent::create();
    }

    /**
     * @inheritDoc
     * @throws PermissionDeniedException
     */
    public function validateToggle()
    {
        WCF::getSession()->checkPermissions($this->permissionsToggle);
    }

    /**
     * @inheritDoc
     */
    public function toggle()
    {
        if (empty($this->objects)) {
            $this->readObjects();
        }

        foreach ($this->objects as $quiz) {
            $quiz->toggle();
        }
    }

    /**
     * Validate loadData method.
     * @throws UserInputException
     */
    public function validateLoadQuiz()
    {
        $this->quiz = $this->getSingleObject();

        if ($this->quiz instanceof DatabaseObjectDecorator) {
            $this->quiz = $this->quiz->getDecoratedObject();
        }
    }

    /**
     * @return array
     * @throws SystemException
     */
    public function loadQuiz()
    {
        $data = $this->quiz->getData();
        $data['questionList'] = [];
        $data['goalList'] = [];

        // load questions
        $questionList = new QuestionList($this->quiz);
        $questionList->readObjects();
        foreach ($questionList as $question) {
            $data['questionList'][] = $question->getData();
        }

        // load goals
        $goalList = new GoalList($this->quiz);
        $goalList->readObjects();
        foreach ($goalList as $goal) {
            /** @var $goal Goal */
            $data['goalList'][$goal->points] = $goal;
        }

        return $data;
    }

    /**
     * @throws PermissionDeniedException
     */
    public function validateImport()
    {
        $this->validateCreate();
    }

    /**
     * Imports a quiz by given a json string or json file
     * @return IStorableObject
     * @throws SystemException
     * @throws DatabaseQueryException
     */
    public function import()
    {
        if (!empty($this->parameters['data']['text'])) {
            $data = ArrayUtil::trim(JSON::decode($this->parameters['data']['text']));
        } else {
            $file = $this->parameters['file'][0];
            $data = ArrayUtil::trim(JSON::decode(file_get_contents($file->getLocation())));
        }

        // import base information for quiz
        $quizData['type'] = $data['type'] ?? 'fun';
        $quizData['title'] = $data['title'] ?? WCF::getLanguage()->get('wcf.acp.quizMaker.import.defaultTitle');
        $quizData['description'] = $data['description'] ?? '';
        $quizData['creationDate'] = TIME_NOW;

        // language information
        if (isset($data['languageCode'])) {
            if (LanguageFactory::getInstance()->multilingualismEnabled()) {
                $language = LanguageFactory::getInstance()->getLanguageByCode($data['languageCode']);

                $quizData['languageID'] = ($language !== null) ? $language->languageID : LanguageFactory::getInstance()->getContentLanguageIDs()[0];

            }
        }

        // create quiz
        $quiz = QuizEditor::create($quizData);

        // import questions
        $questions = $goals = 0;
        if (isset($data['questions']) && count($data['questions'])) {
            foreach ($data['questions'] as $question) {
                $question['quizID'] = $quiz->getObjectID();

                QuestionEditor::create($question);
                $questions++;
            }
        }

        // import goals
        if (isset($data['goals']) && count($data['goals'])) {
            foreach ($data['goals'] as $goal) {
                $goal['quizID'] = $quiz->getObjectID();

                GoalEditor::create($goal);
                $goals++;
            }
        }

        // update counters
        $quizEditor = new QuizEditor($quiz);
        $quizEditor->update(['questions' => $questions, 'goals' => $goals]);

        return $quiz;
    }
}
