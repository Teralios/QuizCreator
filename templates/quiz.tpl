{* sidebar *}
{capture assign='sidebarRight'}
    {if $bestPlayers !== null && $bestPlayers|count > 0}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.quiz.box.bestPlayers{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                {foreach from=$bestPlayers item=player}
                    {assign var="user" value=$player->getUser()}
                    <li class="box24">
                        <a href="{@$user->getLink()}">{@$user->getAvatar()->getImageTag(24)}</a>
                        <div class="sidebarItemTitle">
                            <h3><a href="{@$user->getLink()}">{$user->username}</a></h3>
                            <small>{#$player->score} <b>{lang}wcf.quizCreator.game.score{/lang}</b></small>
                        </div>
                    </li>
                {/foreach}
                </ul>
                <p><a href="{link controller='QuizPlayerList' object=$quiz}{/link}">{lang}wcf.quizCreator.quiz.showPlayers{/lang}</a></p>
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
                                <small>{@$player->playedTime|time}</small>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </section>
    {/if}
{/capture}

{* variables *}
{capture assign='pageTitle'}{$quiz->getTitle()}{/capture}
{capture assign='contentTitle'}{$quiz->getTitle()}{/capture}

{* template *}
{include file='header'}

{include file='__quizInformation'}

{if $__wcf->session->getPermission('user.quiz.canPlay')}
    <div class="section gameContainer" id="gameContainer" data-id="{$quiz->quizID}">
        <div class="gameField dummy">
            <div class="gameHeader">
                <div class="questionCounter"><b>{lang}wcf.quizCreator.game.questions{/lang}</b></div>
                <div class="clock"><b>{lang}wcf.quizCreator.game.time{/lang}</b></div>
                <div class="currentQuestionValue">{lang}wcf.quizCreator.game.points{/lang}</div>
            </div>
            <div class="gameContent">
                <p>Dummy Question</p>
                <ul class="answerList">
                    <li><button>Dummy 1</button></li>
                    <li><button>Dummy 2</button></li>
                    <li><button>Dummy 3</button></li>
                    <li><button>Dummy 4</button></li>
                </ul>
                <button>{lang}wcf.quizCreator.game.next{/lang}</button>
            </div>
            <div class="gameFooter">
                <p>{lang}wcf.quizCreator.game.score{/lang}</p>
            </div>
        </div>
    </div>
{/if}

{if $__wcf->session->getPermission('user.quiz.canPlay')}
    <script data-relocate="true">
        require(['Teralios/QuizCreator/Quiz', 'Language'], function (Quiz, Language) {
            Language.add('wcf.quizCreator.game.finish', '{jslang}wcf.quizCreator.game.finish{/jslang}');
            Language.add('wcf.quizCreator.game.next', '{jslang}wcf.quizCreator.game.next{/jslang}');
            Language.add('wcf.quizCreator.game.points', '{jslang}wcf.quizCreator.game.points{/jslang}');
            Language.add('wcf.quizCreator.game.questions', '{jslang}wcf.quizCreator.game.questions{/jslang}');
            Language.add('wcf.quizCreator.game.score', '{jslang}wcf.quizCreator.game.score{/jslang}');
            Language.add('wcf.quizCreator.game.start', '{jslang}wcf.quizCreator.game.start{/jslang}');
            Language.add('wcf.quizCreator.game.time', '{jslang}wcf.quizCreator.game.time{/jslang}');
            Language.add('wcf.quizCreator.game.lastPosition', '{jslang}wcf.quizCreator.game.lastPosition{/jslang}');
            Language.add('wcf.quizCreator.game.otherPlayers', '{jslang}wcf.quizCreator.game.otherPlayers{/jslang}');
            Language.add('wcf.quizCreator.game.noGoal', '{jslang}wcf.quizCreator.game.noGoal{/jslang}');
            Language.add('wcf.quizCreator.game.noGoal.description', '{jslang}wcf.quizCreator.game.noGoal.description{/jslang}');
            Language.add('wcf.quizCreator.game.missingData', '{jslang}wcf.quizCreator.game.missingData{/jslang}');

            new Quiz(elById('gameContainer'));
        });
    </script>
{/if}

{include file='footer'}