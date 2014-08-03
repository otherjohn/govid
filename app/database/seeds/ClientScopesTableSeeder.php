<?php

class ClientScopesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('oauth_client_scopes')->delete();

        DB::table('oauth_client_scopes')->insert( array(
            array(
                'client_id'    => '1',
                'scope_id' => '1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '1',
                'scope_id' => '2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '1',
                'scope_id' => '3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'scope_id' => '1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'scope_id' => '3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'scope_id' => '2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'scope_id' => '3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
