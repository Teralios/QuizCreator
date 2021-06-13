<?php

namespace wcf\system\box;

use wcf\data\quiz\game\Game;
use wcf\page\QuizPage;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

/**
 * Class QuizUserResultBoxController
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\system\box
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since 1.5.0
 */
class QuizUserResultBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    /**
     * @throws \wcf\system\database\exception\DatabaseQueryException
     * @throws \wcf\system\database\exception\DatabaseQueryExecutionException
     * @throws \wcf\system\exception\SystemException
     */
    public function loadContent(): void
    {
        if (
            /** @scrutinizer ignore-call */ RequestHandler::getInstance()->getActiveRequest() !== null
            && /** @scrutinizer ignore-call */ RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizPage
            && WCF::getUser()->userID
        ) {
            $quiz = /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->quiz ?? null;

            if ($quiz !== null) {
                $game = Game::getMatch($quiz, WCF::getUser()->userID);
                $this->content = WCF::getTPL()->fetch('__quizBoxUserResult', 'wcf', ['game' => $game]);
            }
        }
    }
}
