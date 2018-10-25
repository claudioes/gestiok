<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRoleTable extends Migration
{
    public function up()
    {
        $this->schema->create('user_role', function (Blueprint $table) {
            $table->smallInteger('user_id')->unsigned();
            $table->foreign('user_id', 'fk_user_role_user')
                ->references('id')
                ->on('user')
                ->onDelete('cascade')
            ;
            $table->smallInteger('role_id')->unsigned();
            $table->foreign('role_id', 'fk_user_role_role')
                ->references('id')
                ->on('role')
                ->onDelete('cascade')
            ;
            $table->primary(['user_id', 'role_id']);
        });
    }

    public function down()
    {
        $this->schema->drop('user_role');
    }
}
