<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests\Controller;

use Jolicht\DogadoHealthcheckBundle\Check\CheckInterface;
use Jolicht\DogadoHealthcheckBundle\CheckCollection;
use Jolicht\DogadoHealthcheckBundle\Controller\HealthcheckAction;
use Jolicht\DogadoHealthcheckBundle\Result;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\Controller\HealthcheckAction
 */
class HealthcheckActionTest extends TestCase
{
    public function testInvokeValidResultCollectionReturnsJsonResponseHttpOk(): void
    {
        $result = new Result(name: 'testName', valid: true, message: 'testMessage', context: ['test' => 'context']);
        $check = $this->createMock(CheckInterface::class);
        $check
            ->method('__invoke')
            ->willReturn($result);

        $checkCollection = new CheckCollection();
        $checkCollection->addCheck($check);

        $action = new HealthcheckAction($checkCollection);
        $response = $action->__invoke();

        $expectedContent = json_encode([
            'results' => [
                [
                    'name' => 'testName',
                    'valid' => true,
                    'message' => 'testMessage',
                    'context' => [
                        'test' => 'context',
                    ],
                ],
            ],
        ]);

        $this->assertSame(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertSame($expectedContent, $response->getContent());
    }

    public function testInvokeInvalidResultCollectionReturnsJsonResponseHttpServiceUnavailable(): void
    {
        $result = new Result(name: 'testName', valid: false, message: 'testMessage', context: ['test' => 'context']);
        $check = $this->createMock(CheckInterface::class);
        $check
            ->method('__invoke')
            ->willReturn($result);

        $checkCollection = new CheckCollection();
        $checkCollection->addCheck($check);

        $action = new HealthcheckAction($checkCollection);
        $response = $action->__invoke();

        $expectedContent = json_encode([
            'results' => [
                [
                    'name' => 'testName',
                    'valid' => false,
                    'message' => 'testMessage',
                    'context' => [
                        'test' => 'context',
                    ],
                ],
            ],
        ]);

        $this->assertSame(JsonResponse::HTTP_SERVICE_UNAVAILABLE, $response->getStatusCode());
        $this->assertSame($expectedContent, $response->getContent());
    }
}
