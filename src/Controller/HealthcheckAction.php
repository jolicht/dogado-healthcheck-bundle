<?php

namespace Jolicht\DogadoHealthcheckBundle\Controller;

use Jolicht\DogadoHealthcheckBundle\CheckCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class HealthcheckAction
{
    public function __construct(
        private readonly CheckCollection $checkCollection
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $resultCollection = $this->checkCollection->__invoke();

        return new JsonResponse(
            [
                'results' => $resultCollection,
            ],
            $resultCollection->isValid() ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE
        );
    }
}
