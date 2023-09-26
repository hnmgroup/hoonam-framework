<?php

namespace Hoonam\Framework\Persistence;

use Hoonam\Framework\Application\UnitOfWork;
use Illuminate\Support\Facades\DB;

class DbUnitOfWork implements UnitOfWork
{
    private int $_level;

    public function __construct()
    {
        $this->_level = -1;
    }

    public function begin(): void
    {
        if ($this->_level === -1) DB::beginTransaction();
        $this->_level += 1;
    }

    public function rollback(): void
    {
        DB::rollBack();
        $this->_level = -1;
    }

    public function commit(): void
    {
        if ($this->_level === 0) DB::commit();
        $this->_level -= 1;
    }
}
