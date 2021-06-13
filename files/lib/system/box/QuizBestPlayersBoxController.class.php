<?php

namespace wcf\system\box;

// imports
use wcf\page\QuizPage;
use wcf\system\cache\builder\QuizGameCacheBuilder;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

/**
 * Class QuizBestPlayersBoxController
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\system\box
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since 1.5.0
 */
class QuizBestPlayersBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    /**
     * @throws \wcf\system\exception\SystemException
     */
    protected function loadContent(): void
    {
        $bestPlayers = null;

        if (
            /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest() !== null
            && /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizPage
        ) {
            $quiz = /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->quiz ?? null;
            if ($quiz !== null) {
                $bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                    'context' => 'best',
                    'quizID' => $quiz->quizID,
                    'withUser' => true,
                ]);
            }
        } else {
            $bestPlayers = /** @scrutinizer ignore-call */QuizGameCacheBuilder::getInstance()->getData([
                'context' => 'best',
                'withQuiz' => true,
                'withUser' => true,
            ]);
        }

        if (is_array($bestPlayers) && count($bestPlayers)) {
            $this->content = WCF::getTPL()->fetch('__quizBoxBestPlayers', 'wcf', ['bestPlayers' => $bestPlayers]);
        }
    }
}
