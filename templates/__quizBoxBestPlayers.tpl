{if $bestPlayers !== null && $bestPlayers|count > 0}
    <ul class="sidebarItemList">
        {foreach from=$bestPlayers item=player}
            {assign var="user" value=$player->getUser()}
            {assign var="bestBoxQuiz" value=$player->getQuiz()}
            <li class="box24">
                {@$user->getAvatar()->getImageTag(24)}
                <div class="sidebarItemTitle">
                    <h3>{user object=$user}</a></h3>
                    {if $bestBoxQuiz !== null}
                        <small>{lang}wcf.quizCreator.stats.score.relative.quiz{/lang}</small>
                    {else}
                        <small>{#$player->score} <b>{lang}wcf.quizCreator.stats.score{/lang}</b></small>
                    {/if}
                </div>
            </li>
        {/foreach}
    </ul>
    {if $activeQuiz !== null}
        <p class="small text-center"><a href="{link controller='QuizPlayerList' object=$quiz}{/link}">{lang}wcf.quizCreator.player.show.all{/lang}</a></p>
    {/if}
{/if}