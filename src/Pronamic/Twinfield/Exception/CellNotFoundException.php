<?php

namespace Pronamic\Twinfield\Exception;

use Exception;

class CellNotFoundException extends \Exception {


    public function __construct($field, array $existingFields)
    {
        parent::__construct('Could not find field %s in %s', $field, implode(', ', $existingFields));
    }


}