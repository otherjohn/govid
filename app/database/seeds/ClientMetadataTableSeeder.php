<?php

class ClientMetadataTableSeeder extends Seeder {
    //slug, description, website, email

        protected $description = 'In mea autem etiam menandri, quot elitr vim ei, eos semper disputationi id? Per facer clientetere eu,
                              duo et animal maiestatis. Omnesque invidunt mnesarchum ex mel, vis no case senserit dissentias. Te mei
                              minimum singulis inimicus, ne labores accusam necessitatibus vel, vivendo nominavi ne sed. Posidonium
                              scriptorem consequuntur cum ex? Posse fabulas iudicabit in nec, eos cu electram forensibus, pro ei commodo
                              tractatos reformidans. Qui eu lorem augue alterum, eos in facilis pericula mediocritatem?
                              Est hinc legimus oporteat in. Sit ei melius delicatissimi. Duo ex qualisque adolescens!
                              Pri cu solum aeque. Aperiri docendi vituperatoribus has ea';
    public function run()
    {
        DB::table('oauth_client_metadata')->delete();

        DB::table('oauth_client_metadata')->insert( array(
            array(
                'client_id'    => '1',
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '1',
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '1',
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '1',
                'key' => 'slug',
                'value' => 'client1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '2',
                'key' => 'slug',
                'value' => 'client2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'key' => 'email',
                'value' => 'admin@domain.com',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'key' => 'description',
                'value' => $this->description,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'key' => 'website',
                'value' => 'http://gov.nellcorp.com/client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'client_id'    => '3',
                'key' => 'slug',
                'value' => 'client3',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
