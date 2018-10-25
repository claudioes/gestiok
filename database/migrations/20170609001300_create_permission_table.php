<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionTable extends Migration
{
    public function up()
    {
        $this->schema->create('permission', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('description');
        });
    }

    public function down()
    {
        $this->schema->drop('permission');
    }
}
