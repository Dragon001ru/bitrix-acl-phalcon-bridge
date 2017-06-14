<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 16:16
 */

namespace UW\Acl\Strategies;


use UW\Acl\StrategyInterface;

class AndStrategy implements StrategyInterface
{

    /**
     * Разрешить конфликтную ситуацию
     * @param $accessData
     * @return boolean
     */
    public function resolveConflict($accessData)
    {
        return !in_array(false, $accessData);
    }
}