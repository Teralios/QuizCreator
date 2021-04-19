{* sidebar *}
{capture assign='sidebarRight'}
    {* default boxes *}
    {include file='__quizSidebarBoxes'}

    {* user result *}
    {if $game !== null}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.box.user.result{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                    <li>
                        <h3>{lang}wcf.quizCreator.user.play.official{/lang}</h3>
                        <dl class="plain dataList small">
                            <dt><b>{lang}wcf.quizCreator.stats.score{/lang}</b></dt>
                            <dd>{$game->score}</dd>
                        </dl>
                        <dl class="plain dataList small">
                            <dt><b>{lang}wcf.quizCreator.stats.time{/lang}</b></dt>
                            <dd>{$game->getPlayTime()} {lang}wcf.quizCreator.stats.time.minutes{/lang}</dd>
                        </dl>
                    </li>
                    {if $game->lastPlayedTime > 0}
                        <li>
                            <h3>{lang}wcf.quizCreator.user.play.last{/lang}</h3>
                            <dl class="plain dataList small">
                                <dt><b>{lang}wcf.quizCreator.stats.score{/lang}</b></dt>
                                <dd>{$game->lastScore}</dd>
                            </dl>
                            <dl class="plain dataList small">
                                <dt><b>{lang}wcf.quizCreator.stats.time{/lang}</b></dt>
                                <dd>{$game->getPlayTime(true)} {lang}wcf.quizCreator.stats.time.minutes{/lang}</dd>
                            </dl>
                        </li>
                    {/if}
                </ul>
            </div>
            <div class="text-center boxContent">
                <button class="small" id="showUserResult" data-game-id="{#$game->gameID}">
                    {lang}wcf.quizCreator.user.play.show.details{/lang}
                </button>
            </div>
        </section>
    {/if}
{/capture}

{* variables *}
{capture assign='pageTitle'}{$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{$quiz->getTitle()}{/capture}
{capture assign='quizAnchor'}{anchor object=$quiz}{/capture}

{* template *}
{include file='header'}


{include file='footer'}