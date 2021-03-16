<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\category\CategoryNodeTree;

class QuizCategory extends AbstractDecoratedCategory
{
    public const OBJECT_TYPE = 'de.teralios.quizCreator.quiz.category';

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
