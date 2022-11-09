<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests\Check;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Jolicht\DogadoHealthcheckBundle\Check\DoctrineDbalConnectionCheck;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\Check\DoctrineDbalConnectionCheck
 */
class DoctrineDbalConnectionCheckTest extends TestCase
{
    private ContainerInterface|MockObject $container;
    private DoctrineDbalConnectionCheck $check;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->check = new DoctrineDbalConnectionCheck();
        $this->check->setContainer($this->container);
    }

    public function testInvokeHasNoConnectionReturnsInvalidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn(false);

        $this->container
            ->expects($this->never())
            ->method('get');

        $result = $this->check->__invoke();

        $this->assertFalse($result->isValid());
        $this->assertSame('Doctrine DBAL connection not found.', $result->getMessage());
    }

    public function testInvokeContainerGetReturnsNullReturnsInvalidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn(true);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn(null);

        $result = $this->check->__invoke();

        $this->assertFalse($result->isValid());
        $this->assertSame('Doctrine DBAL connection not found.', $result->getMessage());
    }

    public function testInvokeDummyQueryFailsReturnsInvalidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn(true);

        $connection = $this->createStub(Connection::class);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn($connection);

        $platform = $this->createStub(AbstractPlatform::class);
        $platform
            ->method('getDummySelectSQL')
            ->willReturn('SELECT 1');

        $connection
            ->method('getDatabasePlatform')
            ->willReturn($platform);

        $connection
            ->method('executeQuery')
            ->willThrowException(new \Exception('something went wrong'));

        $result = $this->check->__invoke();

        $this->assertFalse($result->isValid());
        $this->assertSame('something went wrong', $result->getMessage());
    }

    public function testInvokeDummyQuerySucceedsReturnsValidResult(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn(true);

        $connection = $this->createStub(Connection::class);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo('doctrine.dbal.default_connection'))
            ->willReturn($connection);

        $platform = $this->createStub(AbstractPlatform::class);
        $platform
            ->method('getDummySelectSQL')
            ->willReturn('SELECT 1');

        $connection
            ->method('getDatabasePlatform')
            ->willReturn($platform);

        $result = $this->check->__invoke();

        $this->assertTrue($result->isValid());
        $this->assertSame('doctrineDbalConnection', $result->getName());
        $this->assertSame('ok', $result->getMessage());
        $this->assertSame([], $result->getContext());
    }
}
