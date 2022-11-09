<?php

namespace Jolicht\DogadoHealthcheckBundle\Check;

use Jolicht\DogadoHealthcheckBundle\Result;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class EnvironmentCheck implements CheckInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const KEY = 'environment';

    public function __invoke(): Result
    {
        try {
            $environment = $this->container->getParameter('kernel.environment');
        } catch (\Throwable $e) {
            return new Result(self::KEY, false, $e->getMessage());
        }

        return new Result(self::KEY, true, 'ok', ['env' => $environment]);
    }
}
