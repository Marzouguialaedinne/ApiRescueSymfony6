<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EntityNotFoundException extends Exception
{
    public function __construct(string $entityName, int $id)
    {
        parent::__construct(
            'Entity of type [' . $entityName . '] for ID \'' . $id . '\' was not found.',
            Response::HTTP_NOT_FOUND
        );
    }
}