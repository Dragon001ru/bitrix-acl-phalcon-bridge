<?

namespace UW\Acl;

use Bitrix\Main\Config\Configuration;
use UW\Acl\Exceptions\UserRolesOperationException;

class Helper
{

    /**
     * Обработчик события ядра Бтрикса
     * @param IConfiguration $configuration
     */
    public static function configureAcl(IConfiguration $configuration)
    {
        $userId = (intval((new \CUser())->GetID()) ?: 0);
        $roleCodesOfUser = self::getRoleCodesOfUser($userId, $configuration);
        $contextConfiguration = $configuration->get('acl');
        $settings = $configuration->get('acl-settings');

        Storage::set(new Acl(new Context($roleCodesOfUser, $contextConfiguration), $settings));
    }

    /**
     * Получить список кодов групп текущего пользователя
     * @param int $userId
     * @param IConfiguration $configuration
     * @return array
     * @throws UserRolesOperationException
     */
    public static function getRoleCodesOfUser($userId, IConfiguration $configuration)
    {
        $userGroups = [];
        $guestGroupCode = $configuration->get('acl')['guestGroupCode'];

        if ($userId === 0) {
            $userGroups[] = (null === $guestGroupCode) ? 'everyone' : $guestGroupCode;

            return $userGroups;
        }

        try {
            $rsUserGroups = \CUser::GetUserGroupEx($userId);

            while ($userGroup = $rsUserGroups->Fetch()) {
                if (!empty($userGroup['STRING_ID']) && 'everyone' !== $userGroup['STRING_ID']) {
                    $userGroups[] = $userGroup['STRING_ID'];
                }
            }
        } catch (\Exception $e) {
            throw new UserRolesOperationException('Ошибка при получении ролей пользователя', 0, $e);
        }

        return $userGroups;
    }
}