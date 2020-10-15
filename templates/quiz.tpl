{* sidebar *}
{capture assign='sidebarRight'}
    {if $bestPlayers !== null && $bestPlayers|count > 0}
        <section class="box">
            <h2 class="boxTitle">{lang}wcf.quizCreator.box.players.best{/lang}</h2>
            <div class="boxContent">
                <ul class="sidebarItemList">
                {foreach from=$bestPlayers item=player}
                    {assign var="user" value=$player->getUser()}
                    <li class="box24">
                        {@$user->getAvatar()->getImageTag(24)}
                        <div class="sidebarItemTitle">
                            <h3>{user object=$user}</h3>
                            <small>{#$player->score} <b>{lang}wcf.quizCreator.stats.score{/lang}</b></small>
                        </div>
                    </li>
                {/foreach}
                </ul>
                <p class="small text-center"><a href="{link controller='QuizPlayerList' object=$quiz}{/link}">{lang}wcf.quizCreator.player.show.all{/lang}</a></p>
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
                        <li class="box24">
                            {@$user->getAvatar()->getImageTag(24)}
                            <div class="sidebarItemTitle">
                                <h3>{user object=$user}</h3>
                                <small>{@$player->playedTime|time}</small>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
        </section>
    {/if}

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
                <button class="small" id="showUserResult" data-quiz-id="{#$quiz->quizID}" data-game-id="{#$game->gameID}">
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

<div class="section quizInformation" id="informationContainer" data-quiz-id="{$quiz->quizID}">
    {assign var="media" value=$quiz->getMedia()}
    {if $media !== null}
        <header>
            {@$media}
        </header>
    {/if}
    {if !$quiz->description|empty}
        <article class="htmlContent">
            {@$quiz->getDescription()}
        </article>
    {/if}
    <footer>
        <div>
            <span>{lang}wcf.quizCreator.stats.questions.detail{/lang}</span>
            <span class="separatorLeft">{lang}wcf.quizCreator.stats.played.detail{/lang}</span>
        </div>

        {if !$tags|empty}
            <div class="tags">
                <ul class="tagList">
                    {foreach from=$tags item=tag}
                        <li><a href="{link controller='Tagged' object=$tag}de.teralios.quizCreator.quiz{/link}" class="tag">{$tag}</a></li>
                    {/foreach}
                </ul>
            </div>
        {/if}
    </footer>
</div>

{if $__wcf->session->getPermission('user.quiz.canPlay')}
    <div class="section quizGame" id="gameContainer" data-quiz-id="{$quiz->quizID}">
        <div class="gameField dummy">
            <div class="gameHeader">
                <div class="questionCounter"><b>{lang}wcf.quizCreator.stats.questions{/lang}</b></div>
                <div class="clock"><b>{lang}wcf.quizCreator.stats.play.time{/lang}</b></div>
                <div class="currentQuestionValue">{lang}wcf.quizCreator.player.points{/lang}</div>
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
                <p>{lang}wcf.quizCreator.player.score{/lang}</p>
            </div>
        </div>
    </div>
{/if}

{if $__wcf->session->getPermission('user.quiz.canPlay')}
    <script data-relocate="true">
        require(['Teralios/QuizCreator/Quiz', 'Language'], function (Quiz, Language) {
            // language variables
            Language.add('wcf.quizCreator.game.start', '{jslang}wcf.quizCreator.game.start{/jslang}');
            Language.add('wcf.quizCreator.game.finish', '{jslang}wcf.quizCreator.game.finish{/jslang}');
            Language.add('wcf.quizCreator.game.next', '{jslang}wcf.quizCreator.game.question.next{/jslang}');
            Language.add('wcf.quizCreator.game.result.asGood', '{jslang}wcf.quizCreator.game.result.asGood{/jslang}');
            Language.add('wcf.quizCreator.game.result.betterAs', '{jslang}wcf.quizCreator.game.result.betterAs{/jslang}');
            Language.add('wcf.quizCreator.game.goal.none', '{jslang}wcf.quizCreator.game.goal.none{/jslang}');
            Language.add('wcf.quizCreator.game.goal.none.detail', '{jslang}wcf.quizCreator.game.goal.none.detail{/jslang}');
            Language.add('wcf.quizCreator.game.data.missing', '{jslang}wcf.quizCreator.game.data.missing{/jslang}');
            Language.add('wcf.quizCreator.stats.points', '{jslang}wcf.quizCreator.stats.points{/jslang}');
            Language.add('wcf.quizCreator.stats.questions', '{jslang}wcf.quizCreator.stats.questions{/jslang}');
            Language.add('wcf.quizCreator.stats.score', '{jslang}wcf.quizCreator.stats.score{/jslang}');
            Language.add('wcf.quizCreator.stats.time', '{jslang}wcf.quizCreator.stats.time{/jslang}');
            Language.add('wcf.quizCreator.quiz.id.invalid', '{jslang}wcf.quizCreator.quiz.id.invalid{/jslang}');
            Language.add('wcf.quizCreator.quiz.loading.error', '{jslang}wcf.quizCreator.quiz.loading.error{/jslang}')

            new Quiz(elById('gameContainer'));
        })
    </script>
{/if}

{if $game !== null}
    <script data-relocate="true">
        require(['Teralios/QuizCreator/Dialog/Player', 'Language'], function(PlayerDialog, Language) {
            Language.add('wcf.quizCreator.user.play.details.dialog.title', '{jslang}wcf.quizCreator.user.play.details.dialog.title{/jslang}')
            PlayerDialog.init();
        })
    </script>
{/if}

{include file='footer'}