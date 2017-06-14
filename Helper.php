<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.06.2017
 * Time: 11:07
 */

namespace UW\Acl;


use Bitrix\Main\Config\Configuration;

class Helper
{

    /**
     * Обработчик события ядра Бтрикса
     */
    public static function configureAcl()
    {
        $groupCodesOfCurrentUser = self::getGroupCodesOfCurrentUser();
        $config = Configuration::getValue('acl');
        $settings = Configuration::getValue('acl-settings');

        AclStorage::set(new Acl(new Context($groupCodesOfCurrentUser, $config), $settings));
    }

    /**
     * Очистить кеш, используемый для ACL
     */
    public static function clearAclCache()
    {

    }


    /**
     * Получить список кодов групп текущего пользователя
     * @return array
     */
    public static function getGroupCodesOfCurrentUser()
    {
        $userId = (new \CUser())->GetID();
        $userGroups = [];

        if ($userId === null) {
            //todo err
        }

        $rsUserGroups = \CUser::GetUserGroupEx($userId);

        while ($userGroup = $rsUserGroups->Fetch()) {
            if (!empty($userGroup['STRING_ID']) && null != $userGroup['STRING_ID']) {
                $userGroups[] = $userGroup['STRING_ID'];
            }
        }


        return $userGroups;
    }
}