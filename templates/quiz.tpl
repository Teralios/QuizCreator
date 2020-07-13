{* sidebar *}
{capture assign='sidebarRight'}
    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quiz.box.bestPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement best players *}
        </div>
    </section>

    <section class="box">
        <h2 class="boxTitle">{lang}wcf.quizMaker.quiz.box.lastPlayers{/lang}</h2>

        <div class="boxContent">
            PLACEHOLDER
            {* @todo implement most played *}
        </div>
    </section>
{/capture}

{* variables *}
{assign var="pageTitle" value=$quiz->getTitle()}
{assign var="contentTitle" value=$quiz->getTitle()}

{* template *}
{include file='header'}

<div class="section quiz">
    <div class="quizHeader">
        {assign var="media" value=$quiz->getMedia()}
        {if !$media|is_null}
            <div class="quizImage">
                {@$media}
            </div>
        {/if}
        <div class="quizDescription">
            {$quiz->getDescription()}
        </div>
    </div>
    <div id="quizGame">
        <div id="quizGameHeader"></div>
        <div id="quizGameContent">
            <button id="quizStart">{lang}wcf.quizMaker.quiz.start{/lang}</button>

            {* <div class="question">Hier könnte ihre Frage stehen, na wie wäre es?</div>
            <ul class="answerList">
                <li><button class="answerA" disabled>Antwort 1</button></li>
                <li><button class="answerB" disabled>Antwort 2 Lange</button></li>
                <li><button class="answerC correctAnswer" disabled>Antwort 3 noch Länger</button></li>
                <li><button class="answerD wrongAnswer" disabled>Antwort 4 Test</button></li>
            </ul> *}
        </div>
        <div id="quizGameFooter" class="invisible"><button class="nextQuestion">nächste Frage</button></div>
    </div>
</div>

<script data-relocate="true">
    require(['Teralios/Quiz/Quiz', 'Language'], function (Quiz, Language) {
        Language.addObject({
            'wcf.quizMaker.play.points': '{lang}wcf.quizMaker.play.points{/lang}',
            'wcf.quizMaker.play.time': '{lang}wcf.quizMaker.play.time{/lang}',
            'wcf.quizMaker.play.start': '{lang}wcf.quizMaker.play.start{/lang}',
            'wcf.quizMaker.play.next': '{lang}wcf.quizMaker.play.next{/lang}',
            'wcf.quizMaker.play.question': '{lang}wcf.quizMaker.play.question{/lang}'
        });

        Quiz.init();
    });
</script>


{include file='footer'}