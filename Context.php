<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.06.2017
 * Time: 16:14
 */

namespace UW\Acl;


/**
 * Содержит информацию о контексте, в рамках которого должен работать ACL
 *
 * Class Context
 * @package UW\Acl
 */
class Context
{
    /**
     * @var array
     */
    private $userRoles;

    /**
     * @var array
     */
    private $configuration;


    function __construct($userRoles, $configuration)
    {
        //todo валидировать данные
        $this->userRoles = $userRoles;
        $this->configuration = $configuration;
    }

    /**
     * @return int
     */
    public function getDefaultAction()
    {
        return ($this->configuration['defaultAction'] == 'allow') ? \Phalcon\Acl::ALLOW : \Phalcon\Acl::DENY;
    }

    /**
     * @return string
     */
    public function getCombineStrategy()
    {
        return $this->configuration['combineStrategy'];
    }

    /**
     * @return array
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @return array
     */
    public function getAvailableRoles()
    {
        return $this->configuration['availableRoles'];
    }

    /**
     * @return array
     */
    public function getAvailableResources()
    {
        return $this->configuration['availableResources'];
    }

    /**
     * @return array
     */
    public function getRights()
    {
        return $this->configuration['rights'];
    }
}