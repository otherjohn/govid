<?php

class ScopesTableSeeder extends Seeder {

    protected $description = 'In mea autem etiam menandri, quot elitr vim ei, eos semper disputationi.';

    public function run()
    {
        DB::table('oauth_scopes')->delete();

        $user_id = User::first()->id;

        DB::table('oauth_scopes')->insert( array(
            array(
                'scope'    => 'scope1',
                'name' => 'Scope 1',
                'description'    => "Read your first name",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'scope'    => 'scope2',
                'name' => 'Scope 2',
                'description'    => "Read your last name",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'scope'    => 'scope3',
                'name' => 'Scope 3',
                'description'    => "Read your full name",
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ))
        );
    }

}
