<?php

namespace Hoonam\Framework\Infrastructure;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

abstract class MigrationBase extends Migration
{
    protected function createAuditColumns(Blueprint $table): void
    {
        $table->dateTime('created_at')->nullable(false);
        $table->bigInteger('created_by')->nullable(false);
        $table->dateTime('updated_at')->nullable(false);
        $table->bigInteger('updated_by')->nullable(false);
    }

    protected function createTimeIntervalColumn(Blueprint $table, string $name, bool $nullable): void
    {
        $table->dateTime($name.'_start')->nullable($nullable);
        $table->dateTime($name.'_end')->nullable($nullable);
    }
}
