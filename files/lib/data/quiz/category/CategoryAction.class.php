<?php

namespace wcf\data\quiz\category;

// imports
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\DatabaseObject;
use wcf\data\package\PackageCache;
use wcf\system\language\I18nHandler;

/**
 * Class CategoryAction
 *
 * @package   de.teralios.de.teralios.quizCreator
 * @author    teralios
 * @copyright ©2021 Teralios.de
 * @license   GNU General Public License <https://www.gnu.org/licenses/gpl-3.0.txt>
 * @since     1.1.0
 */
class CategoryAction extends AbstractDatabaseObjectAction
{
    // inherit variables
    protected $permissionsCreate = ['admin.content.quizCreator.canManage'];
    protected $permissionsDelete = ['admin.content.quizCreator.canManage'];
    protected $permissionsUpdate = ['admin.content.quizCreator.canManage'];
    protected $className = CategoryEditor::class;

    public function create(): DatabaseObject
    {
        $this->parameters['data']['name'] = 'tmp';
        $packageID = /** @scrutinizer ignore-call */PackageCache::getInstance()->getPackageID('de.teralios.quizCreator');

        $object = parent::create();
        $editor = new CategoryEditor($object);
        $editor->update(['name' => Category::getLanguageItem($object)]);

        /** @scrutinizer ignore-call */I18nHandler::getInstance()->save(
            'name',
            Category::getLanguageItem($object),
            'wcf.quizCreator.category',
            $packageID
        );

        return $object;
    }

    public function update()
    {
        parent::update();

        $packageID = /** @scrutinizer ignore-call */PackageCache::getInstance()->getPackageID('de.teralios.quizCreator');
        foreach ($this->objects as $object) {
            /** @scrutinizer ignore-call */I18nHandler::getInstance()->save(
                'name',
                $object->name,
                'wcf.quizCreator.category',
                $packageID
            );
        }
    }
}
