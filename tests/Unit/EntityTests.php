<?php

namespace Tests\Unit;

use Hoonam\Framework\Domain\Entity;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class EntityTests extends TestCase
{
    public function test_entity_works_properly(): void
    {
        // code

        $this->assertTrue(true);
    }

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //
    //     $this->app['config']->set('database.default', 'sqlite');
    //     $this->app['config']->set('database.connections.sqlite', [
    //         'driver' => 'sqlite',
    //         'database' => ':memory:',
    //         'prefix' => '',
    //         'foreign_key_constraints' => true,
    //         'encoding' => 'utf8',
    //     ]);
    //
    //     $this->createSchema();
    // }

    private function createSchema(): void
    {
        Schema::create('book', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('name', 100)->nullable(false);
        });
        Schema::create('book_volume', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->integer('number')->nullable(false);
            $table->bigInteger('book_id')->nullable(false);
            $table->foreign('book_id')->references('id')->on('book')->cascadeOnDelete();
        });
        Schema::create('book_publication', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('name', 100)->nullable(false);
            $table->foreign('id')->references('id')->on('book')->cascadeOnDelete();
        });
        Schema::create('category', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('title', 100)->nullable(false);
        });
        Schema::create('book_category', function (Blueprint $table) {
            $table->bigInteger('book_id')->nullable(false);
            $table->bigInteger('category_id')->nullable(false);
            $table->foreign('book_id')->references('id')->on('book')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('category')->cascadeOnDelete();
            $table->primary(['book_id', 'category_id']);
        });
        Schema::create('author', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->string('name', 100)->nullable(false);
        });
    }
}
