{capture assign="formTitle"}{if $action == 'add'}{lang}wcf.acp.quizMaker.goal.add{/lang}{else}{lang}wcf.acp.quizMaker.goal.edit{/lang}{/if}{/capture}

{assign var="formHeaderTitle" value='wcf.acp.quizMaker.goal.'|concat:$action}

{include file='quizBaseForm'}