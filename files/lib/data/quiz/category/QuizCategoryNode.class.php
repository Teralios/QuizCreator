<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\category\CategoryNode;
use wcf\system\request\LinkHandler;

/**
 * Class QuizCategoryNode
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\data\quiz\category
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryNode extends CategoryNode
{
    /**
     * @return string
     * @throws \wcf\system\exception\SystemException
     */
    public function getLink(): string
    {
        return /** @scrutinizer ignore-call */LinkHandler::getInstance()->getLink('QuizList', ['object' => $this]);
    }
}
