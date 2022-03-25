<?php

namespace ThePay\ApiClient\Tests\Model;

use PHPUnit\Framework\TestCase;
use ThePay\ApiClient\Model\Project;

/**
 * @covers \ThePay\ApiClient\Model\Project
 */
final class ProjectTest extends TestCase
{
    /**
     * @return void
     */
    public function test()
    {
        $project = new Project(4, 'https://test.cz', 'TP6611111597120692744920');

        self::assertSame(4, $project->getProjectId());
        self::assertSame('https://test.cz', $project->getProjectUrl());
        self::assertSame('TP6611111597120692744920', $project->getAccountIban());
    }
}
