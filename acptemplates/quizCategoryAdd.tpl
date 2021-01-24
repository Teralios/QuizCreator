{assign var="__formTitle" value='wcf.acp.quizCreator.category.'|concat:$action}

{capture assign="__contentBody"}
    {@$form->getHtml()}
{/capture}

{include file='_qcFormBase'}