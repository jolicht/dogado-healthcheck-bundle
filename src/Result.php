<?php

namespace Jolicht\DogadoHealthcheckBundle;

final class Result
{
    public function __construct(
        private readonly string $name,
        private readonly bool $valid,
        private readonly string $message,
        private readonly array $context = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
