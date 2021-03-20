<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\IPopoverAction;
use wcf\data\IToggleAction;
use wcf\data\quiz\game\Game;
use wcf\data\quiz\game\GameEditor;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;
use wcf\data\user\UserEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\validator\data\quiz\Quiz as ValidatedQuiz;
use wcf\system\validator\Validator;
use wcf\system\tagging\TagEngine;
use wcf\system\WCF;

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
    // inherit variables
    protected $className = QuizEditor::class;
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];
    protected $permissionsToggle = ['admin.content.quizCreator.canManage'];
    protected $permissionsResetGames = ['admin.content.quizCreator.canManage'];
    protected $permissionsLoadQuiz = ['user.quiz.canView'];
    protected $permissionsPopover = ['user.quiz.canView'];
    protected $permissionFinishGame = ['user.quiz.canPlay'];
    protected $allowGuestAccess = ['loadQuiz', 'finishGame'];
    protected $resetCache = ['delete', 'update', 'toggle'];

    /**
     * @var Quiz
     */
    protected $quiz = null;

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function create()
    {
        // set timestamp
        $this->parameters['data']['creationDate'] = TIME_NOW;

        // description
        $this->parameters['data']['description'] = $this->parameters['description_htmlInputProcessor']->getHtml();

        // create quiz
        /** @var Quiz $quiz */
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
            /** @var Quiz $object */
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

        /** @var QuizEditor $quiz */
        foreach ($this->objects as $quiz) {
            $quiz->toggle();
        }
    }

    /**
     * Validate loadData method.
     * @throws UserInputException|PermissionDeniedException
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
    public function loadQuiz(): array
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
     * @throws UserInputException|PermissionDeniedException
     */
    public function validateFinishGame()
    {
        $this->validateLoadQuiz();
        WCF::getSession()->checkPermissions($this->permissionFinishGame);
    }

    /**
     * Finish match.
     * @return array
     * @throws SystemException|DatabaseQueryException|DatabaseQueryExecutionException
     */
    public function finishGame(): array
    {
        // user data for update
        $userData = [];

        // data
        $userID = WCF::getUser()->getUserID();
        $score = $this->parameters['score'];
        $result = $this->parameters['result'];
        $time = $this->parameters['timeTotal'];

        // build statistic
        $statistic = Game::getStatistic($this->quiz, $score);

        // check user
        if ($this->quiz->isActive) {
            if ($userID != 0) {
                if (!Game::hasPlayed($this->quiz, $userID)) {
                    $game = GameEditor::createGameResult($this->quiz, $userID, $score, $time, $result);
                    $userData = $game->getUserData(WCF::getUser(), true);
                } elseif (($game = Game::getMatch($this->quiz, $userID)) !== null) {
                    $game = new GameEditor($game);
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
     * @return Quiz
     * @throws SystemException
     */
    public function import(): Quiz
    {
        $data = Validator::getLastValidatedData();

        $languageID = $this->parameters['data']['languageID'] ?? 0;
        $categoryID = $this->parameters['data']['categoryID'] ?? null;
        $overrideLanguage = $this->parameters['data']['overrideLanguage'] ?? false;

        if ($data === null || !($data instanceof ValidatedQuiz)) {
            throw new SystemException('Missing validated quiz data.');
        }

        return QuizEditor::importQuiz($data, $languageID, $overrideLanguage, $categoryID);
    }

    /**
     * Check permissions for reset matches.
     * @throws PermissionDeniedException
     */
    public function validateResetGames()
    {
        WCF::getSession()->checkPermissions($this->permissionsResetGames);
    }

    /**
     * Execute reset matches action.
     * @throws DatabaseQueryException|DatabaseQueryExecutionException|SystemException
     */
    public function resetGames()
    {
        GameEditor::deleteForQuizzes($this->objectIDs);
        GameEditor::resetCache();
    }

    /**
     * @inheritdoc
     * @throws PermissionDeniedException|UserInputException
     */
    public function validateGetPopover()
    {
        WCF::getSession()->checkPermissions($this->permissionsPopover);

        // get quiz.
        $quiz = $this->getSingleObject();
        $this->quiz = ($quiz instanceof DatabaseObjectDecorator) ? $quiz->getDecoratedObject() : $quiz;
    }

    /**
     * @inheritdoc
     * @throws SystemException
     * @return string[]
     * @deprecated since 1.5.0
     */
    public function getPopover(): array
    {
        WCF::getTPL()->assign('quiz', new ViewableQuiz($this->quiz));

        return ['template' => WCF::getTPL()->fetch('__quizPopover')];
    }
}
