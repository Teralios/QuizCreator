<?php

namespace wcf\system\cache\builder;

// imports
use wcf\data\quiz\match\MatchList;

class QuizMatchCacheBuilder extends AbstractCacheBuilder
{
    protected $maxLifetime = 300;

    protected function rebuild(array $parameters)
    {
        $context = $parameters['context'] ?? 'best';
        $quizID = $parameters['quizID'] ?? 0;
        $withQuiz = $parameters['withQuiz'] ?? false;
        $withUser = $parameters['withUser'] ?? false;
        $limit = QUIZ_PLAYERS_PER_BOX;

        $list = null;
        switch ($context) {
            case 'last':
                $list = MatchList::lastPlayers($quizID);
                break;
            default:
                $list = MatchList::bestPlayers($quizID);
        }

        if ($withQuiz) {
            $list->withQuiz();
        }

        if ($withUser) {
            $list->withUser();
        }

        $list->sqlLimit = $limit;
        $list->readObjects();

        $matches = [];
        foreach ($list as $match) {
            $matches[] = $match;
        }

        return $matches;
    }
}
