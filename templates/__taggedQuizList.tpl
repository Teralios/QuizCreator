<div class="section">
    {foreach from=$objects item='quiz' name='quizzes'}
        <pre>
            {$quiz|print_r}
        </pre>
    {/foreach}
</div>