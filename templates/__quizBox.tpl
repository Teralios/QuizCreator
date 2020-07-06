<div id="quiz{$quiz->id}" class="box" data-quiz-id="{$quiz->id}">
    <h2 class="boxTitle">box.title</h2>
    <div clas="boxContent">
        <h3>{$quiz->title}</h3>
        <small>{$quiz->description}</small>
        <div class="quizGameBlock">
            <button class="quizStart">{lang}wcf.quizMaker.start{/lang}</button>
        </div>
    </div>
</div>