<?php

namespace Hoonam\Framework;

use Attribute;

class Validator
{
}

#[Attribute]
class Rules
{
    public function __construct(public readonly mixed $rules)
    {
    }
}
