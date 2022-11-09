<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests;

use Jolicht\DogadoHealthcheckBundle\Result;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\Result
 */
class ResultTest extends TestCase
{
    private Result $result;

    protected function setUp(): void
    {
        $this->result = new Result(
            name: 'testName',
            valid: true,
            message: 'testMessage',
            context: ['test' => 'context']
        );
    }

    public function testGetName(): void
    {
        $this->assertSame('testName', $this->result->getName());
    }

    public function testIsValid(): void
    {
        $this->assertTrue($this->result->isValid());
    }

    public function testGetMessage(): void
    {
        $this->assertSame('testMessage', $this->result->getMessage());
    }

    public function testGetContext(): void
    {
        $this->assertSame(['test' => 'context'], $this->result->getContext());
    }

    public function testGetContextDefaultReturnsEmptyArray(): void
    {
        $result = new Result(name: 'testName', valid: true, message: 'testMessage');
        $this->assertSame([], $result->getContext());
    }
}
