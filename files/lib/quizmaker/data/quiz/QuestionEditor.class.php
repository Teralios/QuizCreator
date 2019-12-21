<?php

declare(strict_types=1);

namespace WCF\Quizmaker\Data\Quiz;

// imports
use WCF\Data\DatabaseObjectEditor;

class QuestionEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Question::class;
}
