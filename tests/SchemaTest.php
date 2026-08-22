<?php

namespace HarryGulliford\Firebird\Tests;

use HarryGulliford\Firebird\Tests\Support\MigrateDatabase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaTest extends TestCase
{
    use MigrateDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function table_exists_query_uses_firebird_2_5_compatible_sql()
    {
        DB::connection()->getSchemaBuilder();
        $sql = DB::connection()->getSchemaGrammar()->compileTableExists(null, 'users');

        $this->assertStringContainsString('select count(*)', strtolower($sql));
        $this->assertStringNotContainsString('select exists', strtolower($sql));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_table()
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertFalse(Schema::hasTable('foo'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_column()
    {
        $this->assertTrue(Schema::hasColumn('users', 'id'));
        $this->assertFalse(Schema::hasColumn('users', 'foo'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_view(): void
    {
        if (version_compare($this->app->version(), '10.34.0', '<')) {
            $this->markTestSkipped('The hasView method is only available in Laravel 10.34.0 and above.');
        }

        $this->createViews();

        $this->assertTrue(Schema::hasView('view_all_users'));

        $this->assertFalse(Schema::hasView(uniqid('view_')));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_columns()
    {
        $this->assertTrue(Schema::hasColumns('users', ['id', 'country']));
        $this->assertFalse(Schema::hasColumns('users', ['id', 'foo']));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_table()
    {
        Schema::dropIfExists('foo');

        $this->assertFalse(Schema::hasTable('foo'));

        Schema::create('foo', function (Blueprint $table) {
            $table->string('bar');
        });

        $this->assertTrue(Schema::hasTable('foo'));

        // Clean up...
        Schema::drop('foo');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_an_exception_for_creating_temporary_tables()
    {
        Schema::dropIfExists('foo');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('This database driver does not support temporary tables.');

        $this->assertFalse(Schema::hasTable('foo'));

        Schema::create('foo', function (Blueprint $table) {
            $table->temporary();

            $table->string('bar');
        });

        $this->assertFalse(Schema::hasTable('foo'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_drop_table()
    {
        DB::select('RECREATE TABLE "foo" ("id" INTEGER NOT NULL)');

        $this->assertTrue(Schema::hasTable('foo'));

        Schema::drop('foo');

        $this->assertFalse(Schema::hasTable('foo'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_drop_table_if_exists()
    {
        DB::select('RECREATE TABLE "foo" ("id" INTEGER NOT NULL)');

        $this->assertTrue(Schema::hasTable('foo'));

        Schema::dropIfExists('foo');

        $this->assertFalse(Schema::hasTable('foo'));

        // Run again to check exists = false.

        Schema::dropIfExists('foo');

        $this->assertFalse(Schema::hasTable('foo'));
    }
}
