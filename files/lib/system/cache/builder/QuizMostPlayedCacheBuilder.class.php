<?php

namespace wcf\system\cache\builder;

// imports
use wcf\data\quiz\ViewableQuizList;
use wcf\system\exception\SystemException;

class QuizMostPlayedCacheBuilder extends AbstractCacheBuilder
{
    // inherit vars
    protected $maxLifetime = 300;

    /**
     * @param array $parameters
     * @return array
     * @throws SystemException
     */
    protected function rebuild(array $parameters)
    {
        $limit = $parameters['limit'] ?? 10;

        $mostPlayed = new ViewableQuizList();
        $mostPlayed->withMedia();
        $mostPlayed->getConditionBuilder()->add($mostPlayed->getDatabaseTableAlias() . '.isActive = ?', [1]);
        $mostPlayed->getConditionBuilder()->add('type = ?', ['competition']);
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
