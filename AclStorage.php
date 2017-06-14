<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 17:45
 */

namespace UW\Acl;

/**
 * Хранилище для сохрения получения доступа к объекту Acl
 *
 * Используется (вместо Singleton) для работы с одним экземпляром класса Acl
 *
 * Class AclStorage
 * @package UW\Acl
 */
class AclStorage
{
    /**
     * @var Acl
     */
    private static $acl;

    /**
     * @return Acl
     */
    public static function get()
    {
        return self::$acl;
    }

    /**
     * @param Acl $acl
     */
    public static function set(Acl $acl)
    {
        self::$acl = $acl;
    }
}