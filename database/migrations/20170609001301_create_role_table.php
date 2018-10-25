<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleTable extends Migration
{
    public function up()
    {
        $this->schema->create('role', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique('ux_role_name');
            $table->smallInteger('created_by')->unsigned()->nullable();
            $table->smallInteger('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('role');
    }
}
