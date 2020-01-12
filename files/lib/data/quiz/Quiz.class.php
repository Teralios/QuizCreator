<?php
namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

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
    /**
     * Path for quiz images.
     */
    const IMAGE_DIR = 'images/quizmaker/';

    /**
     * @var string
     */
    protected static $databaseTableName = 'quiz';

    /**
     * @var string
     */
    protected static $databaseTableIndexName = 'quizID';

    /**
     * Returns image with http path or location path.
     *
     * @param bool $usePath
     * @return string
     */
    public function getImage(bool $usePath = true): string
    {
        if (empty($this->image)) {
            return '';
        }

        return (($usePath) ? WCF::getPath() : WCF_DIR) . $this->image;
    }

    /**
     * Returns image for form container.
     *
     * @return string[]
     */
    public function getImageUploadFileLocations(): array
    {
        return (!empty($this->getImage())) ? [$this->getImage(false)] : [];
    }

    /**
     * Returns language code.
     *
     * @return string
     * @throws \wcf\system\exception\SystemException
     */
    public function getLanguageIcon(): string
    {
        if (empty($this->languageID)) {
            return '';
        }

        $icon = 'icon/flag/';
        $icon .= LanguageFactory::fixLanguageCode(LanguageFactory::getInstance()->getLanguage($this->languageID)->languageCode) . '.svg';

        return (file_exists(WCF_DIR . $icon)) ? WCF::getPath() . $icon : '';
    }

    /**
     * Returns name of language.
     *
     * @return string
     * @throws \wcf\system\exception\SystemException
     */
    public function getLanguageName(): string
    {
        if (empty($this->languageID)) {
            return '';
        }

        return LanguageFactory::getInstance()->getLanguage($this->languageID)->languageName;
    }
}
