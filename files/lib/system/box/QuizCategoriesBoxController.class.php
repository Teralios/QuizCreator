<?php

namespace wcf\system\box;

// imports
use wcf\data\quiz\category\QuizCategoryNodeTree;
use wcf\data\quiz\category\QuizCategory;
use wcf\page\QuizListPage;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

/**
 * Class QuizCategoriesBoxController
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\system\box
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since 1.5.0
 */
class QuizCategoriesBoxController extends AbstractBoxController
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
        $categoryList = new QuizCategoryNodeTree(QuizCategory::OBJECT_TYPE);
        $categoryList = $categoryList->getIterator();
        $activeCategory = null;

        if (iterator_count($categoryList)) {
            if (
                /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest() !== null
                && /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizListPage
            ) {
                $activeCategory = /** @scrutinizer ignore-call */RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->category ?? null;
            }
        }

        $this->content = WCF::getTPL()->fetch('__quizBoxCategories', 'wcf', ['categoryList' => $categoryList, 'activeCategory' => $activeCategory]);
    }
}
