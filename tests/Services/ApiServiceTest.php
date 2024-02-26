<?php

declare(strict_types=1);

namespace ThePay\ApiClient\Tests\Services;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;
use ThePay\ApiClient\Model\Project;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\Tests\BaseTestCase;

/**
 * @covers \ThePay\ApiClient\Service\ApiService
 */
final class ApiServiceTest extends BaseTestCase
{
    private ApiService $service;

    /** @var MockObject&ClientInterface */
    private MockObject $httpClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(ClientInterface::class);
        $httpFactory = new HttpFactory();
        $this->service = new ApiService(
            $this->config,
            $this->createMock(SignatureService::class),
            $this->httpClient,
            $httpFactory,
            $httpFactory
        );
    }

    public function testGetProjects(): void
    {
        $expectedProjects = [new Project(5, 'https://some-url', 'TP7711112006468461625654')];
        $mockBody = '[{"project_id":5,"project_url":"https://some-url","account_iban":"TP7711112006468461625654"}]';

        $this->httpClient->expects(self::once())->method('sendRequest')->willReturn(
            new Response(200, [], $mockBody)
        );

        $projects = $this->service->getProjects();

        self::assertEquals($expectedProjects, $projects);
    }
}
