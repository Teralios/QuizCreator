<?php

namespace wcf\system\page\handler;

use wcf\data\page\Page;
use wcf\data\quiz\ViewableQuiz;
use wcf\data\quiz\ViewableQuizList;
use wcf\data\user\online\UserOnline;
use wcf\system\cache\runtime\ViewableQuizRuntimeCache;
use wcf\system\database\exception\DatabaseQueryExecutionException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Class        QuizPageHandler
 * @package     QuizCreator
 * @subpackage  wcf\system\page\handler
 * @author      Karsten (Teralios) Achterrath
 * @copyright   ©2020 Teralios.de
 * @license     GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 */
class QuizPageHandler extends AbstractLookupPageHandler implements IOnlineLocationPageHandler
{
    use TOnlineLocationPageHandler;

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function getLink($objectID): string
    {
        return /** @scrutinizer ignore-call */ViewableQuizRuntimeCache::getInstance()->getObject($objectID)->getLink();
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function isValid($objectID): bool
    {
        return /** @scrutinizer ignore-call */ViewableQuizRuntimeCache::getInstance()->getObject($objectID) !== null;
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function isVisible($objectID = null): bool
    {
        /** @var ViewableQuiz $quiz */
        $quiz = /** @scrutinizer ignore-call */ViewableQuizRuntimeCache::getInstance()->getObject($objectID);

        return ($quiz !== null && $quiz->isActive);
    }

    /**
     * @inheritDoc
     * @throws DatabaseQueryExecutionException|SystemException
     */
    public function lookup($searchString): array
    {
        $quizList = new ViewableQuizList();
        $quizList->withMedia();
        $quizList->getConditionBuilder()->add(
            $quizList->getDatabaseTableAlias() . '.title LIKE ?',
            ['%' . $searchString . '%']
        );
        $quizList->sqlLimit = 10;
        $quizList->readObjects();

        $results = [];
        /** @var ViewableQuiz $quiz */
        foreach ($quizList as $quiz) {
            $results[] = [
                'description' => /** @scrutinizer ignore-call */$quiz->getPreview(),
                'image' => /** @scrutinizer ignore-call */$quiz->getMedia() ? /** @scrutinizer ignore-call */$quiz->getMedia()->getElementTag(48) : '',
                'link' => /** @scrutinizer ignore-call */$quiz->getLink(),
                'objectID' => $quiz->quizID,
                'title' => /** @scrutinizer ignore-call */$quiz->getTitle()
            ];
        }

        return /** @scrutinizer ignore-type */$results;
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function getOnlineLocation(Page $page, UserOnline $user): string
    {
        if ($user->pageObjectID === null) {
            return '';
        }

        /** @var ViewableQuiz $quiz */
        $quiz = /** @scrutinizer ignore-call */ViewableQuizRuntimeCache::getInstance()->getObject($user->pageObjectID);
        if ($quiz === null || /** @scrutinizer ignore-call */!$quiz->canSee()) {
            return '';
        }

        return WCF::getLanguage()->getDynamicVariable('wcf.page.onlineLocation.' . $page->identifier, ['quiz' => $quiz->getDecoratedObject()]);
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function prepareOnlineLocation(Page $page, UserOnline $user)
    {
        if ($user->pageObjectID !== null) {
            /** @scrutinizer ignore-call */ViewableQuizRuntimeCache::getInstance()->cacheObjectID($user->pageObjectID);
        }
    }
}
