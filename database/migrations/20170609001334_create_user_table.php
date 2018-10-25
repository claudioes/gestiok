<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->schema->create('user', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('email')->unique('ux_user_email');
            $table->string('password');
            $table->string('name');
            $table->boolean('is_active')->default('0');
            $table->boolean('is_admin')->default('0');
            $table->string('remember_token')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        $this->schema->table('role', function (Blueprint $table) {
            $table->foreign('created_by', 'fk_role_created')
                ->references('id')
                ->on('user')
            ;
            $table->foreign('updated_by', 'fk_role_updated')
                ->references('id')
                ->on('user')
            ;
        });
    }

    public function down()
    {
        if ( ! $this->isSQLite()) {
            $this->schema->table('role', function (Blueprint $table) {
                $table->dropForeign('fk_role_created');
                $table->dropForeign('fk_role_updated');
            });
        }

        $this->schema->drop('user');
    }
}
