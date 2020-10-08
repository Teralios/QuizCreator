<div class="section quiz" id="quiz{$quiz->quizID}">
    {assign var="media" value=$quiz->getMedia()}
    <div class="information">
        {if !$media|is_null || !$quiz->description|empty}
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
        {/if}
        <div class="statistic">
            <div>
                <span>{#$quiz->questions} {lang}wcf.quizCreator.questions{/lang}</span>
                <span class="separatorLeft">{#$quiz->played} {lang}wcf.quizCreator.played{/lang}</span>
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
</div>