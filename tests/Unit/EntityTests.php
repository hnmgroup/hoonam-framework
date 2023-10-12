<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Hoonam\Framework\Domain\AggregateRoot;
// use Illuminate\Database\ConnectionResolver;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Schema\Builder;
// use Illuminate\Database\SQLiteConnection;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Schema;
// use PDO;

class EntityTests extends TestCase
{
    public function test_entity_works_properly(): void
    {
        // $conn = new PDO('sqlite::memory:?foreign_keys=1&charset=utf8');
        // $db = new SQLiteConnection($conn);
        // DB::connection('');
        // Model::setConnectionResolver(new ConnectionResolver(['default' => $db]));
        // $schema = new Builder($db);

        // $schema->create('person', function (Blueprint $table) {
        //     $table->bigInteger('id')->autoIncrement();
        //     $table->string('name', 100)->nullable();
        //     $table->dateTime('created_at')->nullable(false);
        //     $table->bigInteger('created_by')->nullable(false);
        //     $table->dateTime('updated_at')->nullable(false);
        //     $table->bigInteger('updated_by')->nullable(false);
        // });
        // $p = new Person;
        // $p->setName('ALI');

        // $p->saveAll();

        // $this->assertTrue(true);
    }
}

class Person extends AggregateRoot
{
    public function name(): string { return $this->getAttributeValue('name'); }
    public function setName(string $value): void { $this->setAttribute('name', $value); }

    protected $table = 'person';
}
