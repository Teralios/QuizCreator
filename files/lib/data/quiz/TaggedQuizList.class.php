<?php

namespace wcf\data\quiz;

// imports
use wcf\system\exception\SystemException;
use wcf\system\tagging\TagEngine;

/**
 * Class        TaggedQuizList
 * @package     QuizCreator
 * @subpackage  wcf\data\quiz
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class TaggedQuizList extends ViewableQuizList
{
    /**
     * TaggedQuizList constructor.
     * @param array $tags
     * @throws SystemException
     */
    public function __construct(array $tags)
    {
        parent::__construct();

        $tagSQL = /** @scrutinizer ignore-call */TagEngine::getInstance()->getSubselectForObjectsByTags(Quiz::OBJECT_TYPE, $tags);
        $this->getConditionBuilder()->add('quizID IN (' . $tagSQL['sql'] . ')', $tagSQL['parameters']);
    }
}
