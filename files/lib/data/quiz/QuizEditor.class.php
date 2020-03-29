<?php
namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\system\file\upload\UploadFile;
use wcf\system\WCF;
use wcf\util\FileUtil;
use wcf\util\ImageUtil;

/**
 * Class QuizEditor
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 *
 * @method string getImage(bool $usePath)
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $type
 * @property-read string $image
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $stages
 */
class QuizEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Quiz::class;

    /**
     * Increment counters for quiz.
     *
     * @param bool $questions
     */
    public function incrementCounter(bool $questions = true)
    {
        $data = [];

        if ($questions === true) {
            $data['questions'] = $this->questions + 1;
        } else {
            $data['stages'] = $this->stages + 1;
        }

        $this->update($data);
    }

    /**
     * Activate or deactivate a quiz.
     */
    public function toggle()
    {
        $this->update(['isActive' => ($this->isActive) ? 0 : 1]);
    }

    /**
     * Return name for quiz image.
     * @param int $quizID
     * @param UploadFile $image
     * @return string
     */
    public static function getImageFileName(int $quizID, UploadFile $image): string
    {
        return Quiz::IMAGE_DIR . 'quiz_' . $quizID . '.' . ImageUtil::getExtensionByMimeType(FileUtil::getMimeType($image->getLocation()));
    }

    public static function updateCounterAfterDelete(int $quizID, int $counter, bool $questions = true)
    {
        $field = ($questions === true) ? 'questions' : 'stages';
        $sql = 'UPDATE  ' . static::getDatabaseTAbleNAme() . '
                SET     ' . $field . ' = ' . $field . ' - ?
                WHERE   quizID = ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$counter, $quizID]);
    }
}
