<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\IPopoverAction;
use wcf\data\IStorableObject;
use wcf\data\IToggleAction;
use wcf\data\quiz\match\Match;
use wcf\data\quiz\match\MatchEditor;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;
use wcf\data\user\UserEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\quiz\validator\Validator;
use wcf\system\tagging\TagEngine;
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
class QuizAction extends AbstractDatabaseObjectAction implements IToggleAction, IPopoverAction
{
    // inherit vars
    protected $className = QuizEditor::class;
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];
    protected $permissionsToggle = ['admin.content.quizCreator.canManage'];
    protected $permissionsResetMatches = ['admin.content.quizCreator.canManage'];
    protected $permissionsLoadQuiz = ['user.quiz.canView'];
    protected $permissionsPopover = ['user.quiz.canView'];
    protected $permissionFinishGame = ['user.quiz.canPlay'];
    protected $allowGuestAccess = ['loadQuiz', 'finishMatch'];
    protected $resetCache = ['delete', 'update', 'toggle'];

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

        // description
        $this->parameters['data']['description'] = $this->parameters['description_htmlInputProcessor']->getHtml();

        // create quiz
        $quiz = parent::create();

        // tags
        if (!empty($this->parameters['tags'])) {
            /** @scrutinizer ignore-call */TagEngine::getInstance()->addObjectTags(
                Quiz::OBJECT_TYPE,
                $quiz->getObjectID(),
                $this->parameters['tags'],
                $quiz->languageID ?? /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getDefaultLanguageID()
            );
        }

        // create database entry
        return $quiz;
    }

    /**
     * @inheritdoc
     * @throws SystemException
     */
    public function update()
    {
        // description
        $this->parameters['data']['description'] = $this->parameters['description_htmlInputProcessor']->getHtml();

        parent::update();

        // tags
        if (!empty($this->parameters['tags'])) {
            foreach ($this->objects as $object) {
                /** @scrutinizer ignore-call */TagEngine::getInstance()->addObjectTags(
                    Quiz::OBJECT_TYPE,
                    $object->getObjectID(),
                    $this->parameters['tags'],
                    $object->languageID ?? /** @scrutinizer ignore-call */LanguageFactory::getInstance()->getDefaultLanguageID()
                );
            }
        }
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
        // check permission
        WCF::getSession()->checkPermissions($this->permissionsLoadQuiz);

        // get quiz.
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
            $data['goalList'][] = $goal->getData();
        }

        return $data;
    }

    /**
     * Validate finish game.
     * @throws UserInputException
     * @throws PermissionDeniedException
     */
    public function validateFinishMatch()
    {
        $this->validateLoadQuiz();
        WCF::getSession()->checkPermissions($this->permissionFinishGame);
    }

    /**
     * Finish match.
     * @return array
     * @throws SystemException
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public function finishMatch()
    {
        // user data for update
        $userData = [];

        // data
        $userID = WCF::getUser()->getUserID();
        $score = $this->parameters['score'];
        $result = $this->parameters['result'];
        $time = $this->parameters['timeTotal'];

        // build statistic
        $statistic = Match::getStatistic($this->quiz, $score);

        // check user
        if ($this->quiz->isActive) {
            if ($userID != 0) {
                if (!Match::hasPlayed($this->quiz, $userID)) {
                    $game = MatchEditor::createGameResult($this->quiz, $userID, $score, $time, $result);
                    $userData = $game->getUserData(WCF::getUser(), true);
                } elseif (($game = Match::getMatch($this->quiz, $userID)) !== null) {
                    $game = new MatchEditor($game);
                    $game->update(['lastScore' => $score, 'lastPlayedTime' => TIME_NOW, 'lastTimeTotal' => $time]);
                    $userData = $game->getUserData(WCF::getUser());
                }

                // update user
                $userEditor = new UserEditor(WCF::getUser());
                $userEditor->update($userData);
            }

            // update quiz
            $quizEditor = new QuizEditor($this->quiz);
            $quizEditor->updatePlayed();
        }

        // maybe reset game caches for quiz?
        return $statistic;
    }

    /**
     * Validates import action.
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
        $data = Validator::getLastValidatedData();

        if ($data === null) {
            throw new SystemException('Missing validated quiz data.');
        }

        return QuizEditor::importQuiz($data);
    }

    /**
     * Check permissions for reset matches.
     * @throws PermissionDeniedException
     */
    public function validateResetMatches()
    {
        WCF::getSession()->checkPermissions($this->permissionsResetMatches);
    }

    /**
     * Execute reset matches action.
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public function resetMatches()
    {
        MatchEditor::deleteForQuizzes($this->objectIDs);
        MatchEditor::resetCache();
    }

    /**
     * @inheritdoc
     * @throws PermissionDeniedException
     * @throws SystemException
     * @throws UserInputException
     */
    public function validateGetPopover()
    {
        WCF::getSession()->checkPermissions($this->permissionsPopover);

        // get quiz.
        $this->quiz = $this->getSingleObject();
        if ($this->quiz instanceof DatabaseObjectDecorator) {
            $this->quiz = new ViewableQuiz($this->quiz->getDecoratedObject());
        }
    }

    /**
     * @inheritdoc
     */
    public function getPopover()
    {
        WCF::getTPL()->assign('quiz', $this->quiz);

        return ['template' => WCF::getTPL()->fetch('__quizPopover')];
    }
}
