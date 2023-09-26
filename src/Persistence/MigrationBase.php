<?php

namespace Hoonam\Framework\Persistence;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

abstract class MigrationBase extends Migration
{
    protected function id(Blueprint $table): void
    {
        $table->bigInteger('id')->autoIncrement();
    }

    protected function createAuditColumns(Blueprint $table): void
    {
        $table->dateTime('created_at')->nullable(false);
        $table->bigInteger('created_by')->nullable(false);
        $table->dateTime('updated_at')->nullable(false);
        $table->bigInteger('updated_by')->nullable(false);
    }

    public function createMetaTagsColumn(Blueprint $table): ColumnDefinition
    {
        return $table->json('meta_tags')->nullable(false);
    }

    public function createTimeIntervalColumn(Blueprint $table, string $name, bool $nullable): void
    {
        $table->dateTime($name.'_start')->nullable($nullable);
        $table->dateTime($name.'_end')->nullable($nullable);
    }
}
