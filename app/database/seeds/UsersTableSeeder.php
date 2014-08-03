<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();


        $users = array(
            array(
                'username'      => 'admin@example.org',
                'pid'      => '111111111',
                'first_name'      => 'John',
                'last_name'      => 'Doe',
                'email'      => 'admin@example.org',
                'password'   => Hash::make('admin'),
                'street'      => '1 Lomb Memorial Drive',
                'city'      => 'Rochester',
                'state'      => 'New York',
                'zip'      => '14623',
                'phone'      => '555-555-5555',
                'mobile'      => '123-456-7890',
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'username'      => 'user@example.org',
                'pid'      => '222222222',
                'first_name'      => 'Jane',
                'last_name'      => 'Doe',
                'email'      => 'user@example.org',
                'password'   => Hash::make('user'),
                'street'      => '1 Lomb Memorial Drive',
                'city'      => 'Rochester',
                'state'      => 'New York',
                'zip'      => '14623',
                'phone'      => '555-555-5555',
                'mobile'      => '123-456-7890',
                'confirmed'   => 1,
                'confirmation_code' => md5(microtime().Config::get('app.key')),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            )
        );

        DB::table('users')->insert( $users );
    }

}
