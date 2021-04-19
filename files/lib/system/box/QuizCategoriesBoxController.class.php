<?php

namespace wcf\system\box;

// imports
use wcf\data\quiz\category\QuizCategoryNodeTree;
use wcf\data\quiz\category\QuizCategory;
use wcf\page\QuizListPage;
use wcf\system\request\RequestHandler;
use wcf\system\WCF;

class QuizCategoriesBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = ['footerBoxes', 'sidebarLeft', 'sidebarRight', 'contentTop', 'contentBottom', 'footer'];

    protected function loadContent()
    {
        $categoryList = new QuizCategoryNodeTree(QuizCategory::OBJECT_TYPE);
        $categoryList = $categoryList->getIterator();
        $activeCategory = null;

        if (iterator_count($categoryList)) {
            if (
                RequestHandler::getInstance()->getActiveRequest() !== null
                && RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof QuizListPage
            ) {
                $activeCategory = RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->category ?? null;
            }
        }

        $this->content = WCF::getTPL()->fetch('__quizBoxCategories', 'wcf', ['categoryList' => $categoryList, 'activeCategory' => $activeCategory]);
    }
}
