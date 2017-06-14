<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 16:15
 */

namespace UW\Acl;


class Combinator
{
    public function run(StrategyInterface $strategy, $accessData)
    {
        return $strategy->resolveConflict($accessData);
    }
}