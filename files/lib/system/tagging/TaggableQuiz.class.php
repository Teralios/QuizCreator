<?php

namespace wcf\system\tagging;

// imports

class TaggableQuiz extends AbstractCombinedTaggable
{

    public function getTemplateName()
    {
        return '__taggedQuizList';
    }

    /**
     * @inheritDoc
     */
    public function getObjectListFor(array $tags) {
        return new TaggedQuizList($tags);
    }
}
