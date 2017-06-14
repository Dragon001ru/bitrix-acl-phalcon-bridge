<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 16:17
 */

namespace UW\Acl\Strategies;


use UW\Acl\StrategyInterface;

class XorStrategy implements StrategyInterface
{

    /**
     * Разрешить конфликтную ситуацию
     * @param $accessData
     * @return boolean
     */
    public function resolveConflict($accessData)
    {
        // TODO: Implement resolveConflict() method.
    }
}