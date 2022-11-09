<?php

namespace Jolicht\DogadoHealthcheckBundle;

use Jolicht\DogadoHealthcheckBundle\Check\CheckInterface;

final class CheckCollection
{
    /**
     * @var CheckInterface[]
     */
    private array $checks = [];

    public function addCheck(CheckInterface $check): void
    {
        $this->checks[] = $check;
    }

    /**
     * @return CheckInterface[]
     */
    public function getChecks(): array
    {
        return $this->checks;
    }

    public function __invoke(): ResultCollection
    {
        $resultCollection = new ResultCollection();

        foreach ($this->checks as $check) {
            $resultCollection->addResult($check->__invoke());
        }

        return $resultCollection;
    }
}
