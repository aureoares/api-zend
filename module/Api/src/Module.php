<?php

namespace Api;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module.
 * Our API module configuration.
 *
 * @package Api
 */
class Module implements ConfigProviderInterface
{
    /**
     * Get the main configuration for the module.
     *
     * @return array Return module configuration.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Define factories for module services.
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\CompanyTable::class => function($container) {
                    $tableGateway = $container->get(Model\CompanyTableGateway::class);
                    return new Model\CompanyTable($tableGateway);
                },
                Model\CompanyTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Company());
                    return new TableGateway('companies', $dbAdapter, null, $resultSetPrototype);
                },
                Model\TeamTable::class => function($container) {
                    $tableGateway = $container->get(Model\TeamTableGateway::class);
                    return new Model\TeamTable($tableGateway);
                },
                Model\TeamTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Team());
                    return new TableGateway('teams', $dbAdapter, null, $resultSetPrototype);
                },
                Model\MemberTable::class => function($container) {
                    $tableGateway = $container->get(Model\MemberTableGateway::class);
                    return new Model\MemberTable($tableGateway);
                },
                Model\MemberTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Member());
                    return new TableGateway('members', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    /**
     * Define factories for module controllers.
     *
     * @return array
     */
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\CompanyController::class => function($container) {
                    return new Controller\CompanyController(
                        $container->get(Model\CompanyTable::class)
                    );
                },
                Controller\TeamController::class => function($container) {
                    return new Controller\TeamController(
                        $container->get(Model\TeamTable::class),
                        $container->get(Model\MemberTable::class)
                    );
                },
                Controller\MemberController::class => function($container) {
                    return new Controller\MemberController(
                        $container->get(Model\MemberTable::class),
                        $container->get(Model\TeamTable::class)
                    );
                },
            ],
        ];
    }
}
