<?php

namespace App\Exception;

class ExpenseNotFoundException extends \Exception
{
    public function __construct(string $uuid)
    {
        parent::__construct(
            sprintf(
                'No expense found for uuid %s',
                $uuid
            )
        );
    }
}
