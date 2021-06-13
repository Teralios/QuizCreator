<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\category\CategoryNodeTree;

/**
 * Class QuizCategoryNodeTree
 *
 * @package   de.teralios.quizCreator
 * @subpackage wcf\data\quiz\category
 * @author    Teralios
 * @copyright ©2019 - 2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class QuizCategoryNodeTree extends CategoryNodeTree
{
    protected $nodeClassName = QuizCategoryNode::class;
}
