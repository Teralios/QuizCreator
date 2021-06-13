<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\category\CategoryNodeTree;

/**
 * Class QuizCategory
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\data\quiz\category
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategory extends AbstractDecoratedCategory
{
    public const OBJECT_TYPE = 'de.teralios.quizCreator.quiz.category';

    /**
     * @return array
     * @throws \Exception
     */
    public static function getOptions(): array
    {
        $categories = [];

        $categoryList = new CategoryNodeTree(QuizCategory::OBJECT_TYPE);
        $nodeTree = $categoryList->getIterator();
        foreach ($nodeTree as $node) {
            $categories[$node->categoryID] = $node->title;
        }

        return $categories;
    }
}
