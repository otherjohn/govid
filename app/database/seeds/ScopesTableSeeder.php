<?php

class ScopesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('oauth_scopes')->delete();

        $user_id = User::first()->id;

        DB::table('oauth_scopes')->insert( array(
            array(
                'scope'    => 'openid',
                'name' => 'ID Number',
                'description'    => "Read your ID number.",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'scope'    => 'profile',
                'name' => 'Profile',
                'description'    => "Read your Name, Email, Phone and Address",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'scope'    => 'email',
                'name' => 'Name and Email',
                'description'    => "Read your Name and Email address",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
