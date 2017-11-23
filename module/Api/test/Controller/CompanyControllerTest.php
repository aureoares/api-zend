<?php

namespace ApplicationTest\Controller;

use Api\Controller\CompanyController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CompanyControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/api/companies', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('api');
        $this->assertControllerName(CompanyController::class);
        $this->assertControllerClass('CompanyController');
        $this->assertMatchedRouteName('companies');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/api/company', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
