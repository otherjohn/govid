<?php

class ClientsTableSeeder extends Seeder {

protected $description = 'In mea autem etiam menandri, quot elitr vim ei, eos semper disputationi id? Per facer clientetere eu,
                              duo et animal maiestatis. Omnesque invidunt mnesarchum ex mel, vis no case senserit dissentias. Te mei
                              minimum singulis inimicus, ne labores accusam necessitatibus vel, vivendo nominavi ne sed. Posidonium
                              scriptorem consequuntur cum ex? Posse fabulas iudicabit in nec, eos cu electram forensibus, pro ei commodo
                              tractatos reformidans. Qui eu lorem augue alterum, eos in facilis pericula mediocritatem?
                              Est hinc legimus oporteat in. Sit ei melius delicatissimi. Duo ex qualisque adolescens!
                              Pri cu solum aeque. Aperiri docendi vituperatoribus has ea';

public function keygen($length=40)
{
    $key = '';
    list($usec, $sec) = explode(' ', microtime());
    mt_srand((float) $sec + ((float) $usec * 100000));
    
    $inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

    for($i=0; $i<$length; $i++)
    {
        $key .= $inputs{mt_rand(0,61)};
    }
    return $key;
}

    public function run(){

        DB::table('oauth_client_metadata')->delete();
        DB::table('oauth_client_endpoints')->delete();
        DB::table('oauth_client_scopes')->delete();
        DB::table('oauth_client_grants')->delete();
        DB::table('oauth_clients')->delete();

        //$clients = array($this->keygen(), $this->keygen(), $this->keygen());
        $clients = array("nqcXKxMpMxg29R9zYcExaFTAHhiBZK2Db9PXZ96Y", "ekdlKmeJzLO4n7ry4volTzaf1puoHVXQxlGyLi4t", "pRZPmRBXYMG7RYJVF571A3dnFvP7XqDVMDuWSaWU");

        DB::table('oauth_clients')->insert( array(
            array(
                'id'    => $clients[0],
                'secret'    => $this->keygen(),
                'name'      => 'Client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'id'    => $clients[1],
                'secret'    => $this->keygen(),
                'name'      => 'Client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'id'    => $clients[2],
                'secret'    => $this->keygen(),
                'name'      => 'Client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    
DB::table('oauth_client_endpoints')->insert( array(
            array(
                'client_id'    => $clients[0],
                'redirect_uri' => 'http://gov.nellcorp.com/client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'redirect_uri' => 'http://gov.nellcorp.com/client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'redirect_uri' => 'http://gov.nellcorp.com/client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );

DB::table('oauth_client_grants')->insert( array(
            array(
                'client_id'    => $clients[0],
                'grant_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'grant_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'grant_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );

DB::table('oauth_client_scopes')->insert( array(
            array(
                'client_id'    => $clients[0],
                'scope_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[0],
                'scope_id' => 2,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[0],
                'scope_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'scope_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'scope_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'scope_id' => 2,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'scope_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );

DB::table('oauth_client_metadata')->insert( array(
            array(
                'client_id'    => $clients[0],
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[0],
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[0],
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[0],
                'key' => 'slug',
                'value' => 'client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[1],
                'key' => 'slug',
                'value' => 'client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => $clients[2],
                'key' => 'slug',
                'value' => 'client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );

    }

}
