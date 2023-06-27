<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\Services;

use PHPUnit\Framework\MockObject\MockObject;
use ThePay\ApiClient\Http\HttpResponse;
use ThePay\ApiClient\Http\HttpServiceInterface;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Tests\BaseTestCase;

/**
 * @covers \ThePay\ApiClient\Service\ApiService
 */
final class ApiServiceTest extends BaseTestCase
{
    private ApiService $service;

    /** @var MockObject&HttpServiceInterface */
    private MockObject $httpService;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpService = $this->createMock(HttpServiceInterface::class);
        $this->service = new ApiService($this->config, $this->httpService);
    }

    public function testGetProjects(): void
    {
        $expectedProjects = [new Project(5, 'https://some-url', 'TP7711112006468461625654')];
        $mockBody = '[{"project_id":5,"project_url":"https://some-url","account_iban":"TP7711112006468461625654"}]';

        $this->httpService->expects(self::once())->method('get')->willReturn(
            new HttpResponse(
                null,
                200,
                'OK',
                [],
                $mockBody,
            )
        );

        $projects = $this->service->getProjects();

        self::assertEquals($expectedProjects, $projects);
    }
}
