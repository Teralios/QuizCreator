{* content header *}
{capture assign='headContent'}
    {if $pageNo < $pages}
        <link rel="next" href="{link controller='QuizList'}pageNo={@$pageNo+1}{/link}">
    {/if}
    {if $pageNo > 1}
        <link rel="prev" href="{link controller='QuizList'}{if $pageNo > 2}pageNo={@$pageNo-1}{/if}{/link}">
    {/if}
{/capture}

{* sidebar *}
{capture assign='sidebarRight'}
    {if $bestPlayers !== null && $bestPlayers|count > 0}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.quiz.box.bestPlayers{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                    {foreach from=$bestPlayers item=player}
                        {assign var="user" value=$player->getUser()}
                        {assign var="quiz" value=$player->getQuiz()}
                        <li class="box24">
                            <a href="{@$user->getLink()}">{@$user->getAvatar()->getImageTag(24)}</a>
                            <div class="sidebarItemTitle">
                                <h3><a href="{@$user->getLink()}">{$user->username}</a></h3>
                                <small>{lang}wcf.quizCreator.box.score.possible{/lang}</small>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </section>
    {/if}

    {if $lastPlayers !== null && $lastPlayers|count > 0}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.quiz.box.lastPlayers{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                    {foreach from=$lastPlayers item=player}
                        {assign var="user" value=$player->getUser()}
                        <li class="box24">
                            <a href="{@$user->getLink()}">{@$user->getAvatar()->getImageTag(24)}</a>
                            <div class="sidebarItemTitle">
                                <h3><a href="{@$user->getLink()}">{$user->username}</a></h3>
                                <small>{lang}wcf.quizCreator.user.played{/lang}</small>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </section>
    {/if}

    {if $mostPlayed !== null && $mostPlayed|count > 0}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.quizList.box.mostPlayed{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                    {foreach from=$mostPlayed item=$quiz}
                        {assign var="media" value=$quiz->getMedia()}
                        <li class="box24">
                            <a href="{@$quiz->getLink()}">
                                {if $media !== null}
                                    {$media->getElementTag(24)}
                                {else}
                                    <span class="icon icon24 fa-question-circle"></span>
                                {/if}
                            </a>
                            <div class="sidebarItemTitle">
                                <h3><a href="{@$quiz->getLink()}">{$quiz->title}</a></h3>
                                <small>{lang}wcf.quizCreator.quiz.played{/lang}</small>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </section>
    {/if}
{/capture}

{* link *}
{capture assign='linkParameters'}&sortField={$sortField}&sortOrder={$sortOrder}{if !$languageID|empty}&languageID={$languageID}{/if}{/capture}

{* template *}
{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
            {pages print=true assign='pagesLinks' controller='QuizList' link='pageNo=%d&$linkParameters'}
        {/content}
    </div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox messageGroupList">
        <ol class="tabularList">
            <li class="tabularListRow tabularListRowHead">
                <ol class="tabularListColumns">
                    <li class="columnSort">
                        <ul class="inlineList">
                            <li>
                                <a rel="nofollow" href="{link controller='QuizList'}pageNo={@$pageNo}&sortField={$sortField}&sortOrder={if $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
                                    <span class="icon icon16 fa-sort-amount-{$sortOrder|strtolower} jsTooltip" title="{lang}wcf.global.sorting{/lang} ({lang}wcf.global.sortOrder.{if $sortOrder === 'ASC'}ascending{else}descending{/if}{/lang})"></span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <span class="dropdownToggle">{if $sortField == 'title'}{lang}wcf.global.title{/lang}{else}{lang}wcf.quizCreator.{$sortField}{/lang}{/if}</span>

                                    <ul class="dropdownMenu">
                                        {foreach from=$validSortFields item=_sortField}
                                            <li{if $_sortField === $sortField} class="active"{/if}>
                                                <a rel="nofollow" href="{link controller='QuizList'}pageNo={@$pageNo}{if !$languageID|empty}&languageID={$languageID}{/if}&sortField={$_sortField}&sortOrder={if $sortField === $_sortField}{if $sortOrder === 'DESC'}ASC{else}DESC{/if}{else}{$sortOrder}{/if}{/link}">
                                                    {if $_sortField == 'title'}
                                                        {lang}wcf.global.title{/lang}
                                                    {else}
                                                        {lang}wcf.quizCreator.{$_sortField}{/lang}
                                                    {/if}
                                                </a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ol>
            </li>

            {foreach from=$objects item=quiz}
                <li class="tabularListRow">
                    <ol class="tabularListColumns">
                        <li class="columnIcon">
                            <span
                                    class="icon icon32 {if $quiz->type == 'competition'}fa-trophy{else}fa-child{/if} jsTooltip"
                                    title="{lang}wcf.acp.quizCreator.quiz.type.{@$quiz->type}{/lang}">
                            </span>
                        </li>

                        <li class="columnSubject">
                            <h3><a href="{$quiz->getLink()}">{$quiz->title}</a></h3>
                            <small>{@$quiz->creationDate|time}</small>
                        </li>

                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.questions{/lang}</dt>
                                <dd>{@$quiz->questions|shortUnit}</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.players{/lang}</dt>
                                <dd>{@$quiz->players|shortUnit}</dd>
                            </dl>
                            {if $quiz->playery > 0}
                                <dl class="plain statsDataList">
                                    <dt>{lang}wcf.quizCreator.averageScore{/lang}</dt>
                                    <dd>{($quiz->score / $quiz->players)|shortUnit}</dd>
                                </dl>
                            {/if}
                            <div class="messageGroupListStatsSimple" aria-label="{lang}wcf.quizCreator.questions{/lang}"><span class="icon icon16 fa-question-circle"></span> {@$quiz->questions|shortUnit}</div>
                        </li>
                        {if !$quiz->languageID|empty}
                            <li class="columnIcon">
                                <a class="jsTooltip" href="{link controller='QuizList'}languageID={$quiz->languageID}{/link}" title="{lang}wcf.quizCreator.language{/lang}">
                                    <img class="iconFlag" src="{$quiz->getLanguageIcon()}">
                                </a>
                            </li>
                        {/if}

                        {* @TODO implent last player and higehst score *}
                        {event name='columns'}
                    </ol>
                </li>
            {/foreach}
        </ol>
    </div>
{else}
    <p class="info" role="status">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    {hascontent}
        <nav class="contentFooterNavigation">
            <ul>
                {content}{event name='contentFooterNavigation'}{/content}
            </ul>
        </nav>
    {/hascontent}
</footer>

{include file='footer'}