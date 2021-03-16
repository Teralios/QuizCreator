<?php

namespace wcf\system\tagging;

// imports
use wcf\data\quiz\TaggedQuizList;
use wcf\system\exception\SystemException;

/**
 * Class        TaggableQuiz
 * @package     QuizCreator
 * @subpackage  wcf\system\tagging
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class TaggableQuiz extends AbstractCombinedTaggable
{

    /**
     * @inheritdoc
     * @return string
     */
    public function getTemplateName(): string
    {
        return '__taggedQuizList';
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function getObjectListFor(array $tags)
    {
        return new TaggedQuizList($tags);
    }
}
