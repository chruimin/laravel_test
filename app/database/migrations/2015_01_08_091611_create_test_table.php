<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE admins LIKE users');
        DB::statement('CREATE TABLE admins_groups LIKE users_groups');
        Schema::table('admins', function($table){
            $table->string('login')->nullable();
            $table->softDeletes();
        });
        Schema::table('admins_groups', function($table){
            $table->renameColumn('user_id', 'admin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
