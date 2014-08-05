<?php

class UsersTableSeeder extends Seeder {


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

    public function run()
    {
        DB::table('users')->delete();


        $users = array(
            array(
                'username'      => 'admin@example.org',
                'pid'      => $this->keygen(20),
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
                'pid'      => $this->keygen(20),
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
