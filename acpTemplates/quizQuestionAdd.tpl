{capture assign="formTitle"}{if $action == 'add'}{lang}wcf.acp.quizMaker.question.add{/lang}{else}{lang}wcf.acp.quizMaker.question.edit{/lang}{/if}{/capture}
{assign var="formHeaderTitle" value='wcf.acp.quizMaker.question.'|concat:$action}

{include file='quizBaseForm'}