<?php
namespace wcf\data\quiz\question;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

/**
 * Class QuestionEditor
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 * @property-read int $questionID
 * @property-read int $quizID
 * @property-read int $position
 * @property-read string $question
 * @property-read string $optionA
 * @property-read string $optionB
 * @property-read string $optionC
 * @property-read string $optionD
 * @property-read string $answer
 */
class QuestionEditor extends DatabaseObjectEditor
{
    // inherit vars
    protected static $baseClass = Question::class;

    /**
     * @param array $parameters
     * @throws \wcf\system\database\exception\DatabaseQueryException
     */
    public function update(array $parameters = [])
    {
        if ($this->position != $parameters['position']) {
            $this->updatePositions((int) $parameters['position']);
        }

        parent::update($parameters);
    }

    /**
     * @inheritDoc
     * @throws \wcf\system\database\exception\DatabaseQueryException
     */
    public static function create(array $parameters = [])
    {
        if (isset($parameters['position']) && $parameters['position'] == 0) {
            $parameters['position'] = 1;
        }

        if (isset($parameters['quizID'])) {
            static::updatePositionsBeforeCreate($parameters['quizID'], $parameters['position']);
        }

        return parent::create($parameters);
    }

    /**
     * Update positions.
     * @param int $quizID
     * @param int $position
     * @throws \wcf\system\database\exception\DatabaseQueryException
     */
    public static function updatePositionsBeforeCreate(int $quizID, int $position)
    {
        if ($position <= 0) {
            return;
        }

        $sql = 'UPDATE  ' . self::getDatabaseTableName() . '
                SET     position = position + 1
                WHERE   quizID   = ?
                    AND position >= ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quizID, $position]);
    }

    /**
     * Update positions after deletion.
     * @param int $quizID
     * @throws \wcf\system\database\exception\DatabaseQueryException
     * @throws \wcf\system\exception\SystemException
     */
    public static function updatePositionAfterDelete(int $quizID)
    {
        $sql = 'SELECT      questionID, position
                FROM        ' . static::getDatabaseTableName() . '
                WHERE       quizID = ?
                ORDER BY    position';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$quizID]);
        $questions = $statement->fetchObjects(Question::class);

        if (count($questions)) {
            $newPosition = 1;
            foreach ($questions as $question) {
                $editor = new QuestionEditor($question);
                $editor->update(['position' => $newPosition]);
                ++$newPosition;
            }
        }
    }

    /**
     * Update positions of other questions.
     * @param int $newPosition
     * @throws \wcf\system\database\exception\DatabaseQueryException
     */
    protected function updatePositions(int $newPosition)
    {
        if ($newPosition > $this->position) {
            $sql = 'UPDATE ' . static::getDatabaseTableName() . '
                    SET   position = position - 1
                    WHERE position BETWEEN ? AND ?
                        AND quizID = ?';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$this->position, $newPosition, $this->quizID]);
        } else {
            $sql = 'UPDATE ' . static::getDatabaseTableName() . '
                    SET   position = position + 1
                    WHERE position BETWEEN ? AND ?
                        AND quizID = ?';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$newPosition, $this->position, $this->quizID]);
        }
    }
}
