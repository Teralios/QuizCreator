{* variables *}
{capture assign='pageTitle'}{$quiz->getTitle()}{/capture}
{capture assign='quizAnchor'}{anchor object=$quiz}{/capture}
{assign var="__disableContentHeader" value=true}
{assign var="media" value=$quiz->getMedia()}

{* template *}
{include file='header'}
<div class="quizContainer">
    <div id="quiz" class="quiz {if $quiz->isActive}isActive{else}notActive{/if}" data-quiz-id="{#$quiz->quizID}">
        <div class="head">
            <div class="headInner">
                <div class="headIconContainer">
                    <div class="headIconContainerInner">
                        <div class="headIcon"></div>
                    </div>
                </div>
                <div class="headTitleContainer">
                    <h1>{$quiz->getTitle()}</h1>
                    <div class="titleStats">
                        <ul class="inlineList">
                            <li><span class="icon icon16 fa-clock-o"></span> {$quiz->creationDate|date}</li>
                            <li><span class="icon icon16 fa-question-circle-o"></span> {#$quiz->questions} {lang}wcf.quizCreator.stats.questions{/lang}</li>
                            <li itemprop="interactionStatistic" itemscope="" itemtype="http://schema.org/InteractionCounter"><span class="icon icon16 fa-gamepad"></span> {#$quiz->played} {lang}wcf.quizCreator.stats.played{/lang}</li>
                            <li itemprop="interactionStatistic" itemscope="" itemtype="http://schema.org/InteractionCounter"><span class="icon icon16 fa-users"></span> {#$quiz->players} {lang}wcf.quizCreator.stats.players{/lang}</li>
                        </ul>
                    </div>
                </div>
                {if $media !== null}
                    <div class="headImageContainer">
                        {@$media} {* here normal image tag is better for display adjustments *}
                    </div>
                    {if $media->caption}
                        <div class="headImageCaptionContainer">
                            <div class="imageCaption">
                                {if $media->captionEnableHtml}{@$media->caption}{else}{$media->caption}{/if}
                            </div>
                        </div>
                    {/if}
                {/if}
            </div>
        </div>
        <div class="description">
            {@$quiz->getDescription()}
        </div>
        <div class="game">
            {* <div class="gameField">
                <div class="header">
                    <div class="questionInfo">
                        <p>Frage <b>1</b> von <b>10</b></p>
                        <p>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                            <span class="question fa icon16 fa-question-circle"></span>
                        </p>
                    </div>
                    <div class="stopwatch">
                        <p class="top"><span class="fa icon16 fa-circle"></span> +10</p>
                        <p>00:00</p>
                    </div>
                    <div class="score">0 <b>Punkte</b></div>
                </div> *}
                {* <div class="main show">
                    <div class="startView"><button>Start</button></div>
                    <div class="intermissionView">
                        <p>Frage 1</p>
                    </div>
                    <div class="questionView">
                        <p class="question">Testfrage - zur Demonstration ist sie kurz!</p>
                        <ul class="questionList">
                            <li><button>Antwort A</button></li>
                            <li><button disabled="disabled" class="correct">Antwort B</button></li>
                            <li><button disabled="disabled" class="incorrect">Antwort C</button></li>
                            <li><button>Antwort D</button></li>
                        </ul>
                        <div class="next show">
                            <p>Erklärung, wird sichtbar, wenn die Frage geschafft wurde.</p>
                            <button>Nächste Frage</button>
                        </div>
                    </div>
                    <div class="resultView">
                        <div class="goalInfo">
                            <p><span class="fa icon128 fa-rebel"></span></p>
                            <p>Jedi-Padawan</p>
                            <p>Noch ein langen Weg du vor dir Hast.</p>
                        </div>
                        <div class="scoreInfo">
                            <p>128 Punkte</p>
                            <p>∅ 56 Punkte</p>
                            <p>Du hast mehr Punkte als 80% der Spieler erreicht.</p>
                        </div>
                    </div>
                </div>
            </div> *}
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
    </div>
</div>

<script type="application/json" id="js-QuizCreator-Language">
    {
        "wcf.quizCreator.game.button.finish": "{jslang}wcf.quizCreator.game.button.finish{/jslang}", {* Spiel beenden *}
        "wcf.quizCreator.game.button.next": "{jslang}wcf.quizCreator.game.button.next{/jslang}", {* nächste Frage *}
        "wcf.quizCreator.game.button.start": "{jslang}wcf.quizCreator.game.button.start{/jslang}", {* Start *}
        "wcf.quizCreator.game.header.question.prefix": "{jslang}wcf.quizCreator.game.header.question.prefix{/jslang}", {* Frage *}
        "wcf.quizCreator.game.header.question.suffix": "{jslang}wcf.quizCreator.game.header.question.prefix{/jslang}", {* von {$questions *}
        "wcf.quizCreator.game.header.score": "{jslang}wcf.quizCreator.game.header.score{/jslang}", {* Punkte *}
        "wcf.quizCreator.game.header.value": "{jslang}wcf.quizCreator.game.header.value{/jslang}", {* {$currentScore} <b>Punkte</b> *}
        "wcf.quizCreator.game.result.average": "{jslang}wcf.quizCreator.game.result.average{/jslang}", {* ∅ {$averageScore} Punkte *}
        "wcf.quizCreator.game.result.players.other": "{jslang}wcf.quizCreator.game.result.players.other{/jslang}", {* Du hast mehr Punkte als {$playerRelative} % der Spieler erreicht. *}
        "wcf.quizCreator.game.result.players.none": "{jslang}wcf.quizCreator.game.result.players.none{/jslang}", {* Noch hat keiner das Spiel gespielt, du bist der erste! *}
        "wcf.quizCreator.game.result.score": "{jslang}wcf.quizCreator.game.result.score{/jslang}" {*  Punkte *}
    }
</script>

{js application='wcf' file='QuizCreator'}

{include file='footer'}