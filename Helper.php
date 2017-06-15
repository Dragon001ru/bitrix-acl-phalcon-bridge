<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.06.2017
 * Time: 11:07
 */

namespace UW\Acl;


use Bitrix\Main\Config\Configuration;
use UW\Acl\Exceptions\UserRolesOperationException;

class Helper
{

    /**
     * Обработчик события ядра Бтрикса
     */
    public static function configureAcl()
    {
        $userId = (intval((new \CUser())->GetID()) ?: 0);
        $roleCodesOfUser = self::getRoleCodesOfUser($userId);
        $config = Configuration::getValue('acl');
        $settings = Configuration::getValue('acl-settings');

        Storage::set(new Acl(new Context($roleCodesOfUser, $config), $settings));
    }


    /**
     * Получить список кодов групп текущего пользователя
     * @param int $userId
     * @return array
     * @throws UserRolesOperationException
     */
    public static function getRoleCodesOfUser($userId)
    {

        $userGroups = [];
        $guestGroupCode = Configuration::getValue('acl')['guestGroupCode'];

        if ($userId === 0) {
            $userGroups[] = (null === $guestGroupCode) ? 'everyone' : $guestGroupCode;

            return $userGroups;
        }

        try{
            $rsUserGroups = \CUser::GetUserGroupEx($userId);

            while ($userGroup = $rsUserGroups->Fetch()) {
                if (!empty($userGroup['STRING_ID']) && null != $userGroup['STRING_ID']) {
                    $userGroups[] = $userGroup['STRING_ID'];
                }
            }
        } catch (\Exception $e) {
            throw new UserRolesOperationException('Ошибка при получении ролей пользователя', 0, $e);
        }



        return $userGroups;
    }
}