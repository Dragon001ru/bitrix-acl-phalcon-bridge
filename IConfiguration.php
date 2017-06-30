<?php
/**
 * User: agr@ugraweb.ru
 * Date: 30.06.2017
 * Time: 18:00
 */

namespace UW\Acl;

/**
 * Интерфейс для работы с конфигурационными файлами
 * Interface IConfiguration
 * @package UW\Acl
 */
interface IConfiguration
{
    /**
     * Получить знчение
     * @param string $key Ключ
     * @param null $defaultValue Значение по у молчанию, если ключ не найден
     * @return mixed
     */
    public function get($key, $defaultValue = null);

    /**
     * Установить значение
     * @param string $key Ключ
     * @param string $value Значение
     * @return mixed
     */
    public function set($key, $value);
}