<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests;

use Jolicht\DogadoHealthcheckBundle\Check\CheckInterface;
use Jolicht\DogadoHealthcheckBundle\CheckCollection;
use Jolicht\DogadoHealthcheckBundle\Result;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\CheckCollection
 */
class CheckCollectionTest extends TestCase
{
    public function testInvokeReturnsResultCollection(): void
    {
        $firstResult = new Result(name: 'firstCheck', valid: true, message: 'firstTestMessage');
        $firstCheck = $this->createStub(CheckInterface::class);
        $firstCheck
            ->method('__invoke')
            ->willReturn($firstResult);

        $secondResult = new Result(name: 'firstCheck', valid: true, message: 'firstTestMessage');
        $secondCheck = $this->createStub(CheckInterface::class);
        $secondCheck
            ->method('__invoke')
            ->willReturn($secondResult);

        $checkCollection = new CheckCollection();
        $checkCollection->addCheck($firstCheck);
        $checkCollection->addCheck($secondCheck);

        $resultCollection = $checkCollection->__invoke();

        $this->assertCount(2, $resultCollection->getResults());
        $this->assertSame([$firstResult, $secondResult], $resultCollection->getResults());
    }

    public function testAddCheck(): void
    {
        $checkCollection = new CheckCollection();
        $check = $this->createStub(CheckInterface::class);
        $checkCollection->addCheck($check);
        $this->assertSame([$check], $checkCollection->getChecks());
    }
}
