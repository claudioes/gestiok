<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolePermissionTable extends Migration
{
    public function up()
    {
        $this->schema->create('role_permission', function (Blueprint $table) {
            $table->smallInteger('role_id')->unsigned();
            $table->foreign('role_id', 'fk_role_permission_role')
                ->references('id')
                ->on('role')
                ->onDelete('cascade')
            ;
            $table->string('permission_id');
            $table->foreign('permission_id', 'fk_role_permission_permission')
                ->references('id')
                ->on('permission')
                ->onDelete('cascade')
            ;
            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down()
    {
        $this->schema->drop('role_permission');
    }
}
