<?php

namespace wcf\system\cache\builder;

// imports
use wcf\data\quiz\game\GameList;
use wcf\system\exception\SystemException;

/**
 * Class        QuizMatchCacheBuilder
 * @package     QuizCreator
 * @subpackage  wcf\system\cache\builder
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizGameCacheBuilder extends AbstractCacheBuilder
{
    // inherit variables
    protected $maxLifetime = 300;

    /**
     * @inheritdoc
     * @throws SystemException
     */
    protected function rebuild(array $parameters): array
    {
        $context = $parameters['context'] ?? 'best';
        $quizID = $parameters['quizID'] ?? 0;
        $withQuiz = $parameters['withQuiz'] ?? false;
        $withUser = $parameters['withUser'] ?? false;
        $limit = QUIZ_PLAYERS_PER_BOX;

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

        $matches = [];
        foreach ($list as $match) {
            $matches[] = $match;
        }

        return $matches;
    }
}
