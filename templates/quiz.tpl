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
{assign var="showQuizMakerCopyright" value=true}

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
    <div class="game dummy">
        <div class="gameHeader">
            <div class="questionCounter"><b>{lang}wcf.quizMaker.game.questions{/lang}</b></div>
            <div class="clock"><b>{lang}wcf.quizMaker.game.time{/lang}</b></div>
            <div class="currentQuestionValue">{lang}wcf.quizMaker.game.points{/lang}</div>
        </div>
        <div class="gameContent">
                <p>Dummy Question</p>

                <ul class="answerList">
                    <li><button>Dummy 1</button></li>
                    <li><button>Dummy 2</button></li>
                    <li><button>Dummy 3</button></li>
                    <li><button>Dummy 4</button></li>
                </ul>

                <button>{lang}wcf.quizMaker.game.next{/lang}</button>
        </div>
        <div class="gameFooter">
            <p>{lang}wcf.quizMaker.game.score{/lang}</p>
        </div>
    </div>
</div>

<script data-relocate="true">
    require(['Teralios/QuizMaker/Quiz', 'Language'], function (Quiz, Language) {
        Language.add('wcf.quizMaker.game.next', '{lang}wcf.quizMaker.game.next{/lang}');
        Language.add('wcf.quizMaker.game.points', '{lang}wcf.quizMaker.game.points{/lang}');
        Language.add('wcf.quizMaker.game.questions', '{lang}wcf.quizMaker.game.questions{/lang}');
        Language.add('wcf.quizMaker.game.score', '{lang}wcf.quizMaker.game.score{/lang}');
        Language.add('wcf.quizMaker.game.start', '{lang}wcf.quizMaker.game.start{/lang}');
        Language.add('wcf.quizMaker.game.time', '{lang}wcf.quizMaker.game.time{/lang}');

        new Quiz(elById('quiz{$quiz->quizID}'));
    });
</script>

{include file='footer'}