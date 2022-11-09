<?php

namespace Jolicht\DogadoHealthcheckBundle\Tests;

use Jolicht\DogadoHealthcheckBundle\Result;
use Jolicht\DogadoHealthcheckBundle\ResultCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\DogadoHealthcheckBundle\ResultCollection
 */
class ResultCollectionTest extends TestCase
{
    public function testGetResultsDefaultReturnsEmptyArray(): void
    {
        $collection = new ResultCollection();
        $this->assertSame([], $collection->getResults());
    }

    public function testAddResult(): void
    {
        $collection = new ResultCollection();
        $result = new Result(name: 'testName', valid: true, message: 'testMessage');
        $collection->addResult($result);
        $this->assertSame([$result], $collection->getResults());
    }

    public function testIsValidDefaultTrue(): void
    {
        $collection = new ResultCollection();
        $this->assertTrue($collection->isValid());
    }

    public function testIsValidHasOnlyValidResultsReturnsTrue(): void
    {
        $collection = new ResultCollection();
        $collection->addResult(new Result(name: 'testName', valid: true, message: 'testMessage'));
        $this->assertTrue($collection->isValid());
    }

    public function testIsValidHasInvalidResultsReturnsFalse(): void
    {
        $collection = new ResultCollection();
        $collection->addResult(new Result(name: 'testName1', valid: false, message: 'testMessage'));
        $collection->addResult(new Result(name: 'testName2', valid: true, message: 'testMessage'));

        $this->assertFalse($collection->isValid());
    }

    public function testJsonSerialize(): void
    {
        $collection = new ResultCollection();
        $collection->addResult(new Result(name: 'testName1', valid: false, message: 'testMessage1'));
        $collection->addResult(
            new Result(name: 'testName2', valid: true, message: 'testMessage2', context: ['test' => 'context'])
        );

        $expected = [
            [
                'name' => 'testName1',
                'valid' => false,
                'message' => 'testMessage1',
                'context' => [],
            ],
            [
                'name' => 'testName2',
                'valid' => true,
                'message' => 'testMessage2',
                'context' => ['test' => 'context'],
            ],
        ];
        $this->assertSame($expected, $collection->jsonSerialize());
    }
}
