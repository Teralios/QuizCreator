<?php

namespace wcf\system\cache\builder;

// imports
use wcf\data\quiz\ViewableQuizList;
use wcf\system\exception\SystemException;

/**
 * Class        QuizMostPlayedCacheBuilder
 * @package     QuizCreator
 * @subpackage  wcf\system\cache\builder
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizMostPlayedCacheBuilder extends AbstractCacheBuilder
{
    // inherit vars
    protected $maxLifetime = 300;

    /**
     * @param array $parameters
     * @return array
     * @throws SystemException
     */
    protected function rebuild(array $parameters): array
    {
        $limit = QUIZ_PER_BOX;

        $mostPlayed = new ViewableQuizList();
        $mostPlayed->withMedia();
        $mostPlayed->getConditionBuilder()->add($mostPlayed->getDatabaseTableAlias() . '.isActive = ?', [1]);
        $mostPlayed->sqlOrderBy = $mostPlayed->getDatabaseTableAlias() . '.played DESC';
        $mostPlayed->sqlLimit = $limit;
        $mostPlayed->readObjects();

        $quizzes = [];
        foreach ($mostPlayed as $quiz) {
            $quizzes[] = $quiz;
        }

        return $quizzes;
    }
}
