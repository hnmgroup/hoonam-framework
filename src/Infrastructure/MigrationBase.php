<?php

namespace Hoonam\Framework\Infrastructure;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

abstract class MigrationBase extends Migration
{
    protected function id(Blueprint $table, bool $incrementing = true): void
    {
        $table->unsignedBigInteger('id', $incrementing)->nullable(false)->primary();
    }

    protected function foreignId(
        Blueprint $table,
        ?string $tableName = null,
        ?string $column = null,
        bool $nullable = false): void
    {
        $column ??= $tableName.'_id';
        $table->unsignedBigInteger($column)->nullable($nullable);
    }

    protected function foreign(
        Blueprint $table,
        ?string $tableName = null,
        ?string $column = null,
        bool $nullable = false,
        bool $cascadeOnDelete = false,
        bool $nullOnDelete = false,
        bool $restrictOnDelete = true): void
    {
        $column ??= $tableName.'_id';
        $table->unsignedBigInteger($column)->nullable($nullable);
        $foreign = $table->foreign($column)->references('id')->on($tableName);
        if ($cascadeOnDelete) $foreign->cascadeOnDelete();
        else if ($nullOnDelete) $foreign->nullOnDelete();
        else if ($restrictOnDelete) $foreign->restrictOnDelete();
    }

    protected function audits(Blueprint $table): void
    {
        $table->dateTime('created_at')->nullable(false);
        $table->bigInteger('created_by')->nullable(false);
        $table->dateTime('updated_at')->nullable(false);
        $table->bigInteger('updated_by')->nullable(false);
    }

    protected function timeInterval(Blueprint $table, string $prefix = '', bool $nullable = true): void
    {
        if (!empty($prefix)) $prefix .= '_';
        $table->dateTime($prefix.'start')->nullable($nullable);
        $table->dateTime($prefix.'end')->nullable($nullable);
    }
}
