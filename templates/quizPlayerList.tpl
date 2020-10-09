{* variables *}
{capture assign='pageTitle'}{lang}wcf.quizCreator.quiz.playerList{/lang} {if $pageNo > 1}- {lang}wcf.page.pageNo{/lang} {$pageNo}{/if}- {$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{lang}wcf.quizCreator.quiz.playerList{/lang}{/capture}
{capture assign='contentDescription'}<a href="{link controller='Quiz' object=$quiz}{/link}">{lang}wcf.quizCreator.quiz.backTo{/lang}</a>{/capture}

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
                    <li class="columnID">{lang}wcf.quizCreator.players.placement{/lang}</li>
                    <li class="columnUser">{lang}wcf.quizCreator.players.name{/lang}</li>
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
                                <li>{lang}wcf.quizCreator.players.played{/lang} {@$game->playedTime|time}</li>
                                {if $game->lastPlayedTime > 0}<li>{lang}wcf.quizCreator.player.lastPlayed{/lang} {@$game->lastPlayedTime|time}</li>{/if}
                            </ul>
                        </li>
                        <li class="columnStats">
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.player.score{/lang}</dt>
                                <dd>{@$game->score|shortUnit}</dd>
                            </dl>
                            <dl class="plain statsDataList">
                                <dt>{lang}wcf.quizCreator.player.gameTime{/lang}</dt>
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
    <p class="info">{lang}wcf.quizCreator.players.noPlayers{/lang}</p>
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