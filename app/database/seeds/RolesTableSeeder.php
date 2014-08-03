<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = new Role;
        $adminRole->name = 'admin';
        $adminRole->save();

        $personRole = new Role;
        $personRole->name = 'person';
        $personRole->save();

        $user = User::where('username','=','admin@example.org')->first();
        $user->attachRole( $adminRole );

        $user = User::where('username','=','user@example.org')->first();
        $user->attachRole( $personRole );
    }

}
