<?php
namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\system\WCF;
use wcf\system\language\LanguageFactory;
/**
 * Class QuizData
 *
 * @package   de.teralios.QuizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   CC BY-SA 4.0 <https://creativecommons.org/licenses/by-sa/4.0/>
 *
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @proberty-read string $description
 * @property-read string $type
 * @property-read string $image
 * @property-read int creationDate
 * @proberty-read int isActive
 */
class Quiz extends DatabaseObject
{
    const IMAGE_DIR = 'images/quizmaker/';
    protected static $databaseTableName = 'quiz';
    protected static $databaseTableIndexName = 'quizID';

    public function getImage(bool $usePath = true): string
    {
        if (empty($this->image)) {
            return '';
        }

        return (($usePath) ? WCF::getPath() : WCF_DIR) . $this->image;
    }

    public function getImageUploadFileLocations(): array
    {
        return (!empty($this->getImage())) ? [$this->getImage(false)] : [];
    }

    public function getLanguage()
    {
        if (empty($this->languageID)) {
            return '';
        }

        return LanguageFactory::getInstance()->getLanguage($this->languageID)->languageCode;
    }
}
