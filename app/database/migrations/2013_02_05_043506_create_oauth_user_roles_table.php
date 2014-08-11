<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthUserRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('client_id');
            $table->string('role');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            //$table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_user_roles', function ($table) {
            $table->dropForeign('oauth_user_roles_user_id_foreign');
            //$table->dropForeign('oauth_user_roles_client_id_foreign');
        });
        Schema::drop('oauth_user_roles');
    }
}
