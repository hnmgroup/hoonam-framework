<?php

namespace Hoonam\Framework\Infrastructure;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

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
        bool $nullable = false,
        bool $unique = false,
        bool $index = false): void
    {
        $column ??= $tableName.'_id';
        $foreign = $table->unsignedBigInteger($column)->nullable($nullable);
        if ($unique) $foreign = $foreign->unique();
        if ($index) $foreign = $foreign->index();
    }

    protected function foreign(
        Blueprint $table,
        string $tableName,
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
        $this->foreignId($table, column: 'created_by');
        $table->dateTime('updated_at')->nullable(false);
        $this->foreignId($table, column: 'updated_by');
    }

    protected function timeInterval(Blueprint $table, string $prefix = '', bool $nullable = true): void
    {
        if (!empty($prefix)) $prefix .= '_';
        $table->dateTime($prefix.'start')->nullable($nullable);
        $table->dateTime($prefix.'end')->nullable($nullable);
    }

    protected function stringAscii(Blueprint $table, string $name, ?int $length = null): ColumnDefinition
    {
        return $table->string($name, $length)->charset('ascii')->collation('ascii_general_ci');
    }

    protected function enum(Blueprint $table, string $name): ColumnDefinition
    {
        return $table->integer($name);
    }

    protected function stringEnum(Blueprint $table, string $name, ?int $length = 100): ColumnDefinition
    {
        return $this->stringAscii($table, $name, $length);
    }
}
