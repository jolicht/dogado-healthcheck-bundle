<?php

namespace Jolicht\DogadoHealthcheckBundle\Check;

use Jolicht\DogadoHealthcheckBundle\Result;

interface CheckInterface
{
    public function __invoke(): Result;
}
