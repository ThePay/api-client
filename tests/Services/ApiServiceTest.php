<?php

namespace ThePay\ApiClient\Tests\Services;

use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Tests\BaseTestCase;

/**
 * @covers \ThePay\ApiClient\Service\ApiService
 */
final class ApiServiceTest extends BaseTestCase
{
    /** @var ApiService */
    private $service;

    /** @var \Mockery\LegacyMockInterface|\ThePay\ApiClient\Http\HttpServiceInterface */
    private $httpService;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->httpService = \Mockery::mock('ThePay\ApiClient\Http\HttpServiceInterface');
        $this->service = new ApiService($this->config, $this->httpService);
    }

    /**
     * @return void
     */
    public function testGetProjects()
    {
        $expectedProjects = array(new Project(5, 'https://some-url', 'TP7711112006468461625654'));
        $mockBody = '[{"project_id":5,"project_url":"https://some-url","account_iban":"TP7711112006468461625654"}]';

        call_user_func(array($this->httpService, 'shouldReceive'), 'get')
            ->once()
            ->andReturn(new HttpResponse(null, 200, 'OK', array(), $mockBody))
        ;

        $projects = $this->service->getProjects();

        self::assertEquals($expectedProjects, $projects);
    }
}
