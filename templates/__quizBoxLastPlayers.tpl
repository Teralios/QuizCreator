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