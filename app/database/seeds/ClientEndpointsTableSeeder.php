<?php

class ClientEndpointsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('oauth_client_endpoints')->delete();

        DB::table('oauth_client_endpoints')->insert( array(
            array(
                'client_id'    => '1',
                'redirect_uri' => 'http://gov.nellcorp.com/client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'redirect_uri' => 'http://gov.nellcorp.com/client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'redirect_uri' => 'http://gov.nellcorp.com/client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
