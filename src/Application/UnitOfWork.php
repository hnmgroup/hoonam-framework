<?php

namespace Hoonam\Framework\Application;

interface UnitOfWork
{
    public function begin(): void;
    public function rollback(): void;
    public function commit(): void;
}
