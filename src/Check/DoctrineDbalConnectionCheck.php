<?php

namespace Jolicht\DogadoHealthcheckBundle\Check;

use Doctrine\DBAL\Connection;
use Jolicht\DogadoHealthcheckBundle\Result;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class DoctrineDbalConnectionCheck implements CheckInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const KEY = 'doctrineDbalConnection';

    public function __invoke(): Result
    {
        if (false === $this->container->has('doctrine.dbal.default_connection')) {
            return new Result(self::KEY, false, 'Doctrine DBAL connection not found.');
        }

        /** @var Connection|null $connection */
        $connection = $this->container->get('doctrine.dbal.default_connection');

        if (null === $connection) {
            return new Result(self::KEY, false, 'Doctrine DBAL connection not found.');
        }

        try {
            $connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL())->free();
        } catch (\Throwable $e) {
            return new Result(self::KEY, false, $e->getMessage());
        }

        return new Result(self::KEY, true, 'ok');
    }
}
