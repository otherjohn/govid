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
        DB::table('oauth_user_roles')->delete();
        DB::table('users')->delete();
        $pids = array($this->keygen(20), $this->keygen(20),$this->keygen(20));

        $users = array(
            array(
                'username'      => 'admin@example.org',
                'pid'      => $pids[0],
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
                'username'      => 'house@example.org',
                'pid'      => $pids[1],
                'first_name'      => 'Gregory',
                'last_name'      => 'House',
                'email'      => 'house@example.org',
                'password'   => Hash::make('house'),
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
                'pid'      => $pids[2],
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

        DB::table('oauth_user_roles')->insert( array(
            array(
                'user_id'    => DB::table('users')->where('pid', $pids[0])->first()->id,
                'client_id' => 'http://govclient.nellcorp.com',
                'role' => 'admin',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'user_id'    => DB::table('users')->where('pid', $pids[1])->first()->id,
                'client_id' => 'http://govclient.nellcorp.com',
                'role' => 'doctor',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ),
            array(
                'user_id'    => DB::table('users')->where('pid', $pids[2])->first()->id,
                'client_id' => 'http://govclient.nellcorp.com',
                'role' => 'patient',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            )));
    }

}
