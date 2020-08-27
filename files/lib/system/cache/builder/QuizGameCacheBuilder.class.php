<?php

namespace wcf\system\cache\builder;

// imports
use wcf\data\quiz\game\GameList;

class QuizGameCacheBuilder extends AbstractCacheBuilder
{
    protected $maxLifetime = 600; // 10 minutes;

    protected function rebuild(array $parameters)
    {
        $context = $parameters['context'] ?? 'best';
        $quizID = $parameters['quizID'] ?? 0;
        $limit = $parameters['limit'] ?? 10;
        $withQuiz = $parameters['withQuiz'] ?? false;
        $withUser = $parameters['withUser'] ?? false;

        $list = null;
        switch ($context) {
            case 'last':
                $list = GameList::lastPlayers($quizID);
                break;
            default:
                $list = GameList::bestPlayers($quizID);
        }

        if ($withQuiz) {
            $list->withQuiz();
        }

        if ($withUser) {
            $list->withUser();
        }

        $list->sqlLimit = $limit;
        $list->readObjects();

        $games = [];
        foreach ($list as $game) {
            $games[] = $game;
        }

        return $games;
    }
}