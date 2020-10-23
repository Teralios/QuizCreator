<?php

namespace wcf\system\cache\runtime;

// imports
use wcf\data\quiz\ViewableQuizList;

/**
 * Class        ViewableQuizRuntimeCache
 * @package     QuizCreator
 * @subpackage  wcf\system\cache\runtime
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class ViewableQuizRuntimeCache extends AbstractRuntimeCache
{
    // inherit variables
    protected $listClassName = ViewableQuizList::class;
}
