<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\DatabaseObjectEditor;
use wcf\data\quiz\question\Question;
use wcf\data\quiz\question\QuestionEditor;
use wcf\system\database\exception\DatabaseQueryException;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Class CategoryEditor
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.5.0
 */
class CategoryEditor extends DatabaseObjectEditor
{
    // inherit variables
    protected static $baseClass = Category::class;

    /**
     * @param array $parameters
     * @throws DatabaseQueryException
     */
    public function update(array $parameters = [])
    {
        if ($this->position != $parameters['position']) {
            $this->updatePositions((int) $parameters['position']);
        }

        parent::update($parameters);
    }

    /**
     * @inheritdoc
     * @throws DatabaseQueryException
     */
    public static function create(array $parameters = []): Category
    {
        if (!empty($parameters)) {
            static::updatePositionsBeforeCreate((int) $parameters['position'] ?? 1);
        }

        return parent::create($parameters);
    }

    /**
     * Update positions.
     * @param int $position
     * @throws DatabaseQueryException
     */
    public static function updatePositionsBeforeCreate(int $position)
    {
        $sql = 'UPDATE  ' . self::getDatabaseTableName() . '
                SET     position = position + 1
                WHERE   position >= ?';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$position]);
    }

    /**
     * Update positions after deletion.
     * @throws DatabaseQueryException
     * @throws SystemException
     */
    public static function updatePositionAfterDelete()
    {
        $sql = 'SELECT      categoryID
                FROM        ' . static::getDatabaseTableName() . '
                ORDER BY    position';
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        $categories = $statement->fetchObjects(Category::class);

        if (count($categories)) {
            $sql = 'UPDATE ' . static::getDatabaseTableAlias() . '
                    SET     position = ?
                    WHERE   categoryID = ?';
            $statement = WCF::getDB()->prepareStatement($sql);

            $newPosition = 1;
            foreach ($categories as $category) {
                $statement->execute([$newPosition, $category->categoryID]);
                ++$newPosition;
            }
        }
    }

    /**
     * Update positions of other questions.
     * @param int $newPosition
     * @throws DatabaseQueryException
     */
    protected function updatePositions(int $newPosition)
    {
        if ($newPosition > $this->position) {
            $sql = 'UPDATE ' . static::getDatabaseTableName() . '
                    SET   position = position - 1
                    WHERE position BETWEEN ? AND ?';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$this->position, $newPosition]);
        } else {
            $sql = 'UPDATE ' . static::getDatabaseTableName() . '
                    SET   position = position + 1
                    WHERE position BETWEEN ? AND ?';
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute([$newPosition, $this->position]);
        }
    }
}
