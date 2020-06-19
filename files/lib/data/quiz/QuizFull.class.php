<?php

namespace wcf\data\quiz;

// imports
use wcf\data\quiz\goal\GoalList;
use wcf\data\quiz\question\QuestionList;

class QuizFull extends Quiz
{
    protected $questions;
    protected $goals;

    public function __construct(int $id)
    {
        parent::__construct($id);

        $this->readQuestions();
        $this->readGoals();
    }

    protected function readQuestions()
    {
        $this->questions = new QuestionList($this);
    }

    protected function readGoals()
    {
        $this->goals = new GoalList($this);
    }

}