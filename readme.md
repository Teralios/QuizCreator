[![GitHub](https://img.shields.io/github/license/Teralios/quizCreator?style=flat-square)](https://www.gnu.org/licenses/gpl-3.0.txt)[![GitHub release (latest by date including pre-releases)](https://img.shields.io/github/v/release/teralios/quizCreator?include_prereleases&style=flat-square)](https://github.com/Teralios/quizCreator/releases)[![Build Status](https://img.shields.io/travis/Teralios/quizCreator.svg?style=flat-square)](https://travis-ci.org/Teralios/quizCreator)[![Code Quality](https://img.shields.io/scrutinizer/g/Teralios/quizCreator.svg?style=flat-square)](https://scrutinizer-ci.com/g/Teralios/quizCreator/)
# Quiz Creator
Adds a simple quiz system to the [WoltLab® Suite Core(WSC)](https://www.woltlab.com/features/) that allows the team to create quizzes for members of one community.

There are two types of quizzes:
  * __Competition__ - where members compete against each other for the high score. The value of the correct answer drops from 10 points to 1 over time.
  * __Fun__ - Here it doesn't matter how long it takes, there is always 1 point, at the end you can present a funny evaluation via goals.

## Todo
### Beta 1
  - [x] Implement Quiz in frontend
  - [x] Implement base javascript for play

### Beta 2
  - [x] Implement base javascript
    - [x] Overwork Teralios/Quiz/Quiz to Teralios/quizCreator/Game
    - [x] Implement Teralios/quizCreator/Quiz for QuizPage
  - [x] Change image to media
  - [ ] Implement result view for quiz
  - [x] Implement fun quiz support
  - [ ] Implement additional content on QuizList and Quiz
    - [ ] Last Player?
    - [x] average score?
  - [x] Import and Export quiz
    - [x] Import
    - [x] Export
  - [x] Icon support for goals

### Beta 3
  - [ ] Implement more permissions
  - [ ] Implement reaction
  - [ ] Implement trophies
  - [ ] Implement Tags for quiz

### Beta 4
  - [ ] Implement frontend editor for manage quiz
  - [ ] Implement player list for quiz
  - [ ] ???
 
### RC1
  - [ ] Fix issues

### 1.1
  - [ ] Implement playing on QuizList
  - [ ] Implement Quiz-of-the-Day
  - [ ] Prepare for extension "quizCreator Community Edition"

### Beyond 1.1
  - [ ] Eye Candy