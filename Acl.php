<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 16:20
 */

namespace UW\Acl;


use Phalcon\Acl\Adapter\Memory as PhalconAcl;
use UW\Acl\Exceptions\PhalconAclException;

/**
 * Обертка над Phalcon ACL.
 *
  * Class Acl
 * @package UW\Acl
 */
class Acl
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var Combinator
     */
    private $combinator;
    /**
     * @var \Phalcon\Acl
     */
    private $phalconAcl;
    /**
     * @var array
     */
    private $roles;
    /**
     * @var array
     */
    private $resources;
    /**
     * @var array
     */
    private $rights;
    private $settings;

    /**
     * Сконфигурировать объект на основании ролей, ресурсов и прав ролей на эти ресурсы
     */
    private function configure()
    {
        $obCache = new \CPHPCache();
        $cacheId = md5(
            serialize($this->context->getDefaultAction()) .
            serialize($this->context->getCombineStrategy()) .
            serialize($this->context->getRights()) .
            serialize($this->context->getAvailableResources()) .
            serialize($this->context->getAvailableRoles())
        );
        $cacheTime = (empty($this->settings['configuration-cache-time']) ?: 3600);
        $cacheFolder = (empty($this->settings['configuration-cache-folder'] ?: 'acl-cache'));

        if ($obCache->InitCache($cacheTime, 'acl_' . $cacheId, $cacheFolder)) {

            $this->phalconAcl->setDefaultAction($obCache->GetVars()['defaultAction']);
            $this->phalconAcl = unserialize($obCache->GetVars()['phalcon-acl']);

        } elseif ($obCache->StartDataCache()) {
            $obCache->CleanDir($cacheFolder);

            $defaultAction = $this->context->getDefaultAction();

            try {
                $this->phalconAcl->setDefaultAction($defaultAction);

                foreach ($this->roles as $role){
                    if(is_string($role)) {
                        $this->phalconAcl->addRole($role);
                    }

                    if(is_array($role)) {
                        $this->phalconAcl->addRole($role[0], $role[1]);
                    }
                }

                foreach ($this->resources as $resourceName => $resourceActions){
                    $this->phalconAcl->addResource($resourceName, $resourceActions);
                }

                foreach ($this->rights as $role => $rightsData) {
                    foreach ($rightsData as $right){
                        if('allow' == $right['access']) {
                            $this->phalconAcl->allow($role, $right['resource']['name'], $right['resource']['actions']);
                        } elseif('deny' == $right['access']) {
                            $this->phalconAcl->deny($role, $right['resource']['name'], $right['resource']['actions']);
                        } else {
                            continue;
                        }
                    }
                }
            } catch (\Exception $e) {
                throw new PhalconAclException('Возникла ошабка при конфигурации ACL', 0, $e);
            }

            $obCache->EndDataCache(['defaultAction' => $defaultAction, 'phalcon-acl' => serialize($this->phalconAcl)]);
        }
    }

    /**
     * Acl constructor.
     * @param Context $context
     * @param array $settings
     */
    public function __construct(Context $context, $settings)
    {
        $this->context = $context;

        $this->roles = $this->context->getAvailableRoles();
        $this->resources = $this->context->getAvailableResources();
        $this->rights = $context->getRights();

        $this->combinator = new Combinator();
        $this->phalconAcl = new PhalconAcl();
        $this->settings = (is_array($settings) ?: []);

        $this->configure();
    }

    /**
     * Проверить доступность выполнения операци для текущего пользователя
     *
     * @param string $resource Имя ресурса, в котором выполняется операция
     * @param string $operation Имя операции
     * @return bool
     */
    public function isAllowed($resource, $operation)
    {
        $accessByRole = [];
        foreach ($this->context->getUserRoles() as $role){
             $accessByRole[] = $this->phalconAcl->isAllowed($role, $resource, $operation);
        }

        return $this->combinator->run(
            StrategyFactory::build($this->context->getCombineStrategy()),
            $accessByRole
        );
    }
    
}