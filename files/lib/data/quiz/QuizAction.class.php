<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\IStorableObject;
use wcf\data\IToggleAction;
use wcf\data\quiz\game\Game;
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
 * @package   de.teralios.quizCreator
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizAction extends AbstractDatabaseObjectAction implements IToggleAction
{
    // inherit vars
    protected $className = QuizEditor::class;
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];
    protected $permissionsToggle = ['admin.content.quizCreator.canManage'];
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

        // load players, sum score and max score
        $data = array_merge($data, Game::buildStatistic($this->quiz));

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
     */
    public function import()
    {
        if (!empty($this->parameters['data']['text'])) {
            $data = ArrayUtil::trim(JSON::decode($this->parameters['data']['text']));
        } else {
            $file = $this->parameters['file'][0];
            $data = ArrayUtil::trim(JSON::decode(file_get_contents($file->getLocation())));
        }

        return QuizEditor::importQuiz($data);
    }
}
