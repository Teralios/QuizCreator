<?php

namespace wcf\data\quiz;

// imports
use wcf\data\DatabaseObjectDecorator;
use wcf\data\media\ViewableMedia;
use wcf\data\media\ViewableMediaList;
use wcf\system\bbcode\SimpleMessageParser;
use wcf\system\exception\SystemException;
use wcf\system\language\LanguageFactory;

/**
 * Class ViewableQuiz
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2020 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $type
 * @property-read int $mediaID
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $goals
 */
class ViewableQuiz extends DatabaseObjectDecorator
{
    // inherit vars
    protected static $baseClass = Quiz::class;

    /**
     * @var ViewableMedia
     */
    protected $mediaObject = null;


    /**
     * Return description.
     *
     * @param bool $parsed
     * @return string
     * @throws SystemException
     */
    public function getDescription(bool $parsed = true): string
    {
        return ($parsed) ? SimpleMessageParser::getInstance()->/** @scrutinizer ignore-call */parse($this->description) : $this->description;
    }

    public function getMedia() //: ?ViewableMedia
    {
        if (!$this->mediaID && $this->mediaObject === null) {
            $mediaList = new ViewableMediaList();
            $mediaList->getConditionBuilder()->add($mediaList->getDatabaseTableAlias() . '.mediaID = ?', [$this->mediaID]);
            $mediaList->readObjects();

            $this->mediaObject = $mediaList->search($this->mediaID);
        }

        return $this->mediaObject;
    }

    /**
     * Set media object.
     * @param ViewableMedia $media
     */
    public function setMedia(ViewableMedia $media) //: void
    {
        $this->mediaObject = $media;
    }

    /**
     * Returns language code.
     *
     * @return string
     * @throws SystemException
     */
    public function getLanguageIcon(): string
    {
        if (empty($this->languageID)) {
            return '';
        }

        return LanguageFactory::getInstance()->/** @scrutinizer ignore-call */getLanguage($this->languageID)->getIconPath();
    }

    /**
     * Returns language name.
     *
     * @return string
     * @throws SystemException
     */
    public function getLanguageName(): string
    {
        return LanguageFactory::getInstance()->/** @scrutinizer ignore-call */getLanguage($this->languageID)->languageName;
    }
}