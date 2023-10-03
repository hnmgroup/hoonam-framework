<?php

namespace Hoonam\Framework\Application;

interface UserIdentity
{
    public function id(): int;
    public function isAuthenticated(): bool;
}
