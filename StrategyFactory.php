<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 18:14
 */

namespace UW\Acl;


use UW\Acl\Exceptions\CombinatorStrategyNotFoundException;

class StrategyFactory
{
    /**
     * @param $strategyCode
     * @return StrategyInterface
     * @throws CombinatorStrategyNotFoundException
     */
    public static function build($strategyCode)
    {
        $folder = __DIR__ . '/Strategies';
        $nameSpace = 'UW\Acl\Strategies';
        $strategyName = $strategyCode . 'Strategy';

        foreach (scandir($folder) as $file) {
            $fileInfo = pathinfo($folder . '/' . $file);

            if ('php' == $fileInfo['extension']) {

                if($strategyName != $fileInfo['filename']){
                    continue;
                }

                $class = $nameSpace . '\\' . $fileInfo['filename'];

                if (!class_exists($class)) {
                    throw new CombinatorStrategyNotFoundException(sprintf('Класс %s не найден.', $class));

                    continue;
                }

                $reflection = new \ReflectionClass($class);

                if ($reflection->isInstantiable() && $reflection->implementsInterface('\UW\Acl\StrategyInterface')) {

                    return new $class();
                }
            }
        }

        throw new CombinatorStrategyNotFoundException(sprintf('Не удалось найти стратегию с указанным кодом: %s.', $strategyCode));
    }
}