{if $bestPlayers !== null && $bestPlayers|count > 0}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizCreator.box.players.best{/lang}</h2>
        <div class="boxContent">
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
            {if $quiz !== null}
                <p class="small text-center"><a href="{link controller='QuizPlayerList' object=$quiz}{/link}">{lang}wcf.quizCreator.player.show.all{/lang}</a></p>
            {/if}
        </div>
    </section>
{/if}

{if $lastPlayers !== null && $lastPlayers|count > 0}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizCreator.box.players.last{/lang}</h2>
        <div class="boxContent">
            <ul class="sidebarItemList">
                {foreach from=$lastPlayers item=player}
                    {assign var="user" value=$player->getUser()}
                    {assign var="lastBoxQuiz" value=$player->getQuiz()}
                    <li class="box24">
                        {@$user->getAvatar()->getImageTag(24)}
                        <div class="sidebarItemTitle">
                            <h3>{user object=$user}</h3>
                            {if $lastBoxQuiz !== null}
                                <small>{lang}wcf.quizCreator.player.played.quiz{/lang}</small>
                            {else}
                                <small>{@$player->playedTime|time}</small>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </div>
    </section>
{/if}
