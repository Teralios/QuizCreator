<?php

namespace wcf\system\box;

// imports
use wcf\system\cache\builder\QuizMostPlayedCacheBuilder;
use wcf\system\WCF;

/**
 * Class QuizMostPlayedBoxController
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\system\box
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since 1.5.0
 */
class QuizMostPlayedBoxController extends AbstractBoxController
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
        $mostPlayed = /** @scrutinizer ignore-call */QuizMostPlayedCacheBuilder::getInstance()->getData();

        if (count($mostPlayed)) {
            $this->content = WCF::getTPL()->fetch('__quizBoxMostPlayed', 'wcf', ['mostPlayed' => $mostPlayed]);
        }
    }
}
