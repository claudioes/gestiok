<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    protected $schema;
    protected $connection;
    protected $driver;

    public function init()
    {
        $this->schema = (new Capsule)->schema();
        $this->connection = $this->schema->getConnection();
        $this->driver = $this->connection->getDriverName();
    }

    public function isSQLite()
    {
        return $this->driver === 'sqlite';
    }
    
    public function addAudit(Blueprint $table)
    {
        $tableName = $table->getTable();

        $table->smallInteger('created_by')->unsigned();
        $table->foreign('created_by', "fk_{$tableName}_created")->references('id')->on('user');
        $table->timestamp('created_at')->nullable();

        $table->smallInteger('updated_by')->unsigned()->nullable();
        $table->foreign('updated_by', "fk_{$tableName}_updated")->references('id')->on('user');
        $table->timestamp('updated_at')->nullable();
    }

    public function addResponsible(Blueprint $table, bool $nullable = false)
    {
        $tableName = $table->getTable();

        $table->foreign('responsible_id', "fk_{$tableName}_responsible")->references('id')->on('user');

        return $table->smallInteger('responsible_id')->unsigned();
    }

    public function addComplete(Blueprint $table)
    {
        $tableName = $table->getTable();

        $table->date('expiration_date');
        $table->date('completed_at')->nullable();
        $table->smallInteger('completed_by')->unsigned()->nullable();
        $table->foreign('completed_by', "fk_{$tableName}_completed")->references('id')->on('user');
    }
}
