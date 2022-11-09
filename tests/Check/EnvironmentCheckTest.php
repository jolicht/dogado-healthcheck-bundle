<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests\Check;

use Jolicht\DogadoHealthcheckBundle\Check\EnvironmentCheck;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\Check\EnvironmentCheck
 */
class EnvironmentCheckTest extends TestCase
{
    private ContainerInterface|MockObject $container;
    private EnvironmentCheck $check;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->check = new EnvironmentCheck();
        $this->check->setContainer($this->container);
    }

    public function testInvokeHasEnvironmentReturnsValidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('getParameter')
            ->with($this->identicalTo('kernel.environment'))
            ->willReturn('test');

        $result = $this->check->__invoke();
        $this->assertSame('environment', $result->getName());
        $this->assertTrue($result->isValid());
        $this->assertSame('ok', $result->getMessage());
        $this->assertSame(['env' => 'test'], $result->getContext());
    }

    public function testInvokeHasNotEnvironmentReturnsInvalidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('getParameter')
            ->with($this->identicalTo('kernel.environment'))
            ->willThrowException(new \Exception('invalid message'));

        $result = $this->check->__invoke();
        $this->assertSame('environment', $result->getName());
        $this->assertFalse($result->isValid());
        $this->assertSame('invalid message', $result->getMessage());
        $this->assertSame([], $result->getContext());
    }
}
