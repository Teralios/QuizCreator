<?php

namespace wcf\data\quiz;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObjectDecorator;
use wcf\data\IStorableObject;
use wcf\data\IToggleAction;
use wcf\data\quiz\game\Game;
use wcf\data\quiz\game\GameEditor;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\exception\UserInputException;
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
    protected $allowGuestAccess = ['loadQuiz', 'finishGame']; // allowed guest access

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
            $data['goalList'][] = $goal->getData();
        }

        return $data;
    }

    /**
     * Validate finish game.
     * @throws UserInputException
     */
    public function validateFinishGame()
    {
        $this->quiz = $this->getSingleObject();

        if ($this->quiz instanceof DatabaseObjectDecorator) {
            $this->quiz = $this->quiz->getDecoratedObject();
        }
    }

    /**
     * Finish game.
     * @return array
     * @throws SystemException
     * @throws DatabaseQueryException
     * @throws DatabaseQueryExecutionException
     */
    public function finishGame()
    {
        // data
        $userID = WCF::getUser()->getUserID();
        $score = $this->parameters['score'];
        $result = $this->parameters['result'];
        $timeTotal = $this->parameters['timeTotal'];

        // build statistic
        $statistic = Game::buildStatistic($this->quiz);

        if ($statistic['players'] > 0) {
            $statistic['playerWorse'] = Game::getPlayersWorse($this->quiz, $score) / $statistic['players'];
        }

        // check user
        if ($userID != 0) {
            if (!Game::hasPlayed($this->quiz, $userID)) {
                $scorePercent = $score / ($this->quiz->questions * Quiz::MAX_SCORE);
                $data = [
                    'userID' => $userID,
                    'quizID' => $this->quiz->quizID,
                    'score' => $score,
                    'result' => JSON::encode($result),
                    'scorePercent' => $scorePercent,
                    'playedTime' => TIME_NOW,
                    'timeTotal' => $timeTotal
                ];

                GameEditor::create($data);
            } elseif (($game = Game::getGame($this->quiz, $userID)) !== null) {
                $game = new GameEditor($game);
                $game->update(['lastScore' => $score, 'lastPlayedTime' => TIME_NOW]);
            }
        }

        // update quiz
        $quizEditor = new QuizEditor($this->quiz);
        $quizEditor->updatePlayed();

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
        if (!empty($this->parameters['data']['text'])) {
            $data = ArrayUtil::trim(JSON::decode($this->parameters['data']['text']));
        } else {
            $file = $this->parameters['file'][0];
            $data = ArrayUtil::trim(JSON::decode(file_get_contents($file->getLocation())));
        }

        return QuizEditor::importQuiz($data);
    }
}
