<?php

namespace wcf\acp\action;

// imports
use wcf\action\AbstractAction;
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Class QuizExportAction
 *
 * @package    de.teralios.quizMaker
 * @subpackage wcf\acp\action
 * @author     Karsten (Teralios) Achterrath
 * @copyright  ©2020 Teralios.de
 * @license    GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizExportAction extends AbstractAction
{
    /**
     * @var Quiz
     */
    public $quiz;

    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @inheritDoc
     * @throws IllegalLinkException
     * @throws PermissionDeniedException
     */
    public function readParameters()
    {
       parent::readParameters();

        if (!WCF::getSession()->getPermission('admin.content.quizMaker.canManage')) {
            throw new PermissionDeniedException();
        }

        $this->quizID = $_REQUEST['id'] ?? 0;
        $this->quiz = new Quiz((int) $this->quizID);

        if (!$this->quiz->quizID) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function execute()
    {
        // quiz data
        $data = $this->quiz->getData();

        // language
        if ($data['languageID'] !== null) {
            $language = LanguageFactory::getInstance()->getLanguage($data['languageID']);
            $data['languageCode'] = $language->getFixedLanguageCode();
        }

        // remove unneeded data
        unset($data['quizID'], $data['creationDate'], $data['isActive'], $data['mediaID'], $data['languageID']);


        $data['questions'] = [];
        $data['goals'] = [];

        // read questions
        $questions = new QuestionList($this->quiz);
        $questions->readObjects();
        foreach ($questions as $question) {
            $tmp = $question->getData();
            unset($tmp['questionID'], $tmp['quizID']);
            $data['questions'][] = $tmp;
        }

        // read goals
        $goals = new GoalList($this->quiz);
        $goals->readObjects();
        foreach ($goals as $goal) {
            $tmp = $goal->getData();
            unset($tmp['quizID'], $tmp['quizID']);
            $data['goals'][] = $tmp;
        }

        // header
        @header('Content-type: application/json');
        @header('Content-disposition: attachment; filename="quiz-' . $this->quiz->quizID . '.json"');

        // no cache headers
        @header('Pragma: no-cache');
        @header('Expires: 0');

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
