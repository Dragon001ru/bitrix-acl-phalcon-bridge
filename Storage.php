<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 17:45
 */

namespace UW\Acl;

use UW\Acl\Exceptions\AclNotInitializedException;

/**
 * Хранилище для сохрения получения доступа к объекту Acl
 *
 * Используется (вместо Singleton) для работы с одним экземпляром класса Acl
 *
 * Class AclStorage
 * @package UW\Acl
 */
class Storage
{
    /**
     * @var Acl
     */
    private static $acl;

    /**
     * @return Acl
     * @throws AclNotInitializedException
     */
    public static function get()
    {
        if(self::$acl instanceof Acl){
            return self::$acl;
        }

        throw new AclNotInitializedException('Объект ACL не был инициализирован.');
    }

    /**
     * @param Acl $acl
     */
    public static function set(Acl $acl)
    {
        self::$acl = $acl;
    }
}