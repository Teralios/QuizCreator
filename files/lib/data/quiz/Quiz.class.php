<?php

namespace wcf\data\Quiz;

// imports
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\Exception\SystemException;
use wcf\system\language\LanguageFactory;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Class QuizData
 *
 * @package   de.teralios.quizMaker
 * @author    Teralios
 * @copyright ©2019 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * @property-read int $quizID
 * @property-read int $languageID
 * @property-read string $title
 * @property-read string $description
 * @property-read string $type
 * @property-read string $image
 * @property-read int $creationDate
 * @property-read int $isActive
 * @property-read int $questions
 * @property-read int $goals
 */
class Quiz extends DatabaseObject implements ILinkableObject, IRouteController
{
    // inherit vars
    protected static $databaseTableName = 'quiz';
    protected static $databaseTableIndexName = 'quizID';

    // const
    const COMPETITION = 'competition';
    const FUN = 'fun';

    /**
     * Path for quiz images.
     */
    const IMAGE_DIR = 'images/quizmaker/';

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function getLink()
    {
        return LinkHandler::getInstance()->getLink(
            'Quiz',
            [
                'object' => $this,
                'forceFrontend' => true
            ]
        );
    }

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
     * @throws SystemException
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
     * @throws SystemException
     */
    public function getLanguageName(): string
    {
        if (empty($this->languageID)) {
            return '';
        }

        return LanguageFactory::getInstance()->getLanguage($this->languageID)->languageName;
    }
}
