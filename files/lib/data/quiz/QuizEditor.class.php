<?php
namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\system\file\upload\UploadFile;
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
 */
class QuizEditor extends DatabaseObjectEditor
{
    protected static $baseClass = Quiz::class;

    public static function getImageFileName(int $quizID, UploadFile $image): string
    {
        return Quiz::IMAGE_DIR . 'quiz_' . $quizID . '.' . ImageUtil::getExtensionByMimeType(FileUtil::getMimeType($image->getLocation()));
    }
}
