<?php

class GrantsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('oauth_grants')->delete();

        DB::table('oauth_grants')->insert( array(
            array(
                'grant' => 'authorization_code',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
