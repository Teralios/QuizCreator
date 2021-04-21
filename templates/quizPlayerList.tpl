{* variables *}
{capture assign='pageTitle'}{lang}wcf.quizCreator.players.page.title{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {$pageNo}{/if}- {$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{lang}wcf.quizCreator.players.page.title{/lang}{/capture}
{capture assign='contentDescription'}{anchor object=$quiz}{/capture}

{* template *}
{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
        {pages print=true assign='pagesLinks' controller='QuizPlayerList' id=$quiz->quizID link='pageNo=%d&$linkParameters'}
        {/content}
    </div>
{/hascontent}
{if $objects|count}
    {assign var='placement' value=$placementStart}
    <div class="section tabularBox">
        <ol class="tabularList playerList">
            <li class="tabularListRow tabularListRowHead">
                <ol class="tabularListColumns">
                    <li class="columnUser">{lang}wcf.quizCreator.players.players{/lang}</li>
                </ol>
            </li>
            {foreach from=$objects item=game}
                {assign var="user" value=$game->getUser()}
                <li class="tabularListRow">
                    <ol class="tabularListColumns">
                        <li class="columnID">{#$placement}.</li>
                        <li class="columnIcon">
                            {if $placement <= 3}
                                <span class="icon icon32 fa-star place{$placement}"></span>
                            {else}
                                <span class="icon icon32 fa-star-o"></span>
                            {/if}
                        </li>
                        <li class="columnAvatar">{@$user->getAvatar()->getImageTag(48)}</li>
                        <li class="columnUser">
                            <h3>{user object=$user}</h3>
                            <ul class="inlineList dotSeparated small">
                                <li>{lang}wcf.quizCreator.players.played.official{/lang}: {@$game->playedTime|time}</li>
                                {if $game->lastPlayedTime > 0}<li>{lang}wcf.quizCreator.players.played.last{/lang}: {@$game->lastPlayedTime|time}</li>{/if}
                            </ul>
                        </li>
                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.stats.points.average{/lang}</dt>
                                <dd>{@$game->score|shortUnit}</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.stats.time{/lang}</dt>
                                <dd>{@$game->getPlayTime()}</dd>
                            </dl>
                        </li>
                    </ol>
                </li>
                {assign var='placement' value=$placement + 1}
            {/foreach}
        </ol>
    </div>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
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