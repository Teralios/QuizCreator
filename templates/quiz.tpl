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
{assign var="pageTitle" value=$quiz->getTitle()}
{assign var="contentTitle" value=$quiz->getTitle()}

{* template *}
{include file='header'}

<div class="section quiz" id="quiz{$quiz->quizID}" data-id="{$quiz->quizID}">
    {assign var="media" value=$quiz->getMedia()}
    {if !$media|is_null || !$quiz->description|empty}
        <div class="information">
            {if !$media|is_null}
                <div class="image">
                    {@$media}
                </div>
            {/if}
            {if !$quiz->description|empty}
                <div class="description">
                    {@$quiz->getDescription()}
                </div>
            {/if}
        </div>
    {/if}
    {if $__wcf->session->getPermission('user.quiz.canPlay')}
        <div class="game dummy">
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
    {/if}
</div>

{if $__wcf->session->getPermission('user.quiz.canPlay')}
    <script data-relocate="true">
        require(['Teralios/QuizCreator/Quiz', 'Language'], function (Quiz, Language) {
            Language.add('wcf.quizCreator.game.finish', '{lang}wcf.quizCreator.game.finish{/lang}');
            Language.add('wcf.quizCreator.game.next', '{lang}wcf.quizCreator.game.next{/lang}');
            Language.add('wcf.quizCreator.game.points', '{lang}wcf.quizCreator.game.points{/lang}');
            Language.add('wcf.quizCreator.game.questions', '{lang}wcf.quizCreator.game.questions{/lang}');
            Language.add('wcf.quizCreator.game.score', '{lang}wcf.quizCreator.game.score{/lang}');
            Language.add('wcf.quizCreator.game.start', '{lang}wcf.quizCreator.game.start{/lang}');
            Language.add('wcf.quizCreator.game.time', '{lang}wcf.quizCreator.game.time{/lang}');
            Language.add('wcf.quizCreator.game.lastPosition', '{lang}wcf.quizCreator.game.lastPosition{/lang}');
            Language.add('wcf.quizCreator.game.otherPlayers', '{lang}wcf.quizCreator.game.otherPlayers{/lang}');
            Language.add('wcf.quizCreator.game.noGoal', '{lang}wcf.quizCreator.game.noGoal{/lang}');
            Language.add('wcf.quizCreator.game.noGoal.description', '{lang}wcf.quizCreator.game.noGoal.description{/lang}');

            new Quiz(elById('quiz{$quiz->quizID}'));
        });
    </script>
{/if}

{include file='footer'}