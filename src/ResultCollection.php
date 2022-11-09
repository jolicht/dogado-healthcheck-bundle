<?php

namespace Jolicht\DogadoHealthcheckBundle;

final class ResultCollection implements \JsonSerializable
{
    /**
     * @var Result[]
     */
    private array $results = [];

    private bool $valid = true;

    public function addResult(Result $result): void
    {
        if (false === $result->isValid()) {
            $this->valid = false;
        }

        $this->results[] = $result;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function jsonSerialize(): array
    {
        return array_map(function ($result) {
            return [
                'name' => $result->getName(),
                'valid' => $result->isValid(),
                'message' => $result->getMessage(),
                'context' => $result->getContext(),
            ];
        }, $this->results);
    }
}
