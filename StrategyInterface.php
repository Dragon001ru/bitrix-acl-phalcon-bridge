<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 18:11
 */

namespace UW\Acl;

/**
 * Интерфейс для реализации стратегий решения конфликтных ситуаций,
 * возникающих при получении права доступа
 *
 * Interface StrategyInterface
 * @package UW\Acl
 */
interface StrategyInterface
{
    /**
     * Разрешить конфликтную ситуацию
     * @param $accessData
     * @return boolean
     */
    public function resolveConflict($accessData);
}