<?php

namespace wcf\page;

// imports
use wcf\data\quiz\Quiz;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\SystemException;
use wcf\system\page\PageLocationManager;
use wcf\system\WCF;

/**
 * Trait        TQuizPage
 * @package     QuizCreator
 * @subpackage  wcf\page
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
trait TQuizPage
{
    /**
     * @var int
     */
    public $quizID = 0;

    /**
     * @var Quiz
     */
    public $quiz;

    // only for scrutinizer … Look line 49: quiz is active …
    public $isActive;

    /**
     * Base implementation for readParameters for quiz pages.
     * @throws IllegalLinkException|PermissionDeniedException
     */
    public function readQuizParameters(): void
    {
        $this->quizID = $_REQUEST['id'] ?? 0;

        $this->quiz = new Quiz((int) $this->quizID);
        if (!$this->quiz ->quizID) {
            throw new IllegalLinkException();
        }

        if ($this->quiz->isActive == 0 && !WCF::getSession()->getPermission('admin.content.quizCreator.canManage')) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * Set quiz data and tags.
     */
    public function assignQuizData(): void
    {
        WCF::getTPL()->assign([
            'quiz' => $this->quiz,
        ]);
    }

    /**
     * @throws SystemException
     */
    public function setQuizParentLocation(): void
    {
        /** @scrutinizer ignore-call */PageLocationManager::getInstance()->addParentLocation(
            'de.teralios.quizCreator.Quiz',
            $this->quizID,
            $this->quiz
        );
    }
}
