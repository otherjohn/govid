<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        $permissions = array(
            array( // 1
                'name'         => 'manage_apps',
                'display_name' => 'manage apps'
            ),
            array( // 4
                'name'         => 'manage_users',
                'display_name' => 'manage users'
            ),
            array( // 5
                'name'         => 'manage_roles',
                'display_name' => 'manage roles'
            ),
        );

        DB::table('permissions')->insert( $permissions );

        DB::table('permission_role')->delete();

        $role_id_admin = Role::where('name', '=', 'admin')->first()->id;
        $role_id_comment = Role::where('name', '=', 'person')->first()->id;
        $permission_base = (int)DB::table('permissions')->first()->id - 1;

        $permissions = array(
            array(
                'role_id'       => $role_id_admin,
                'permission_id' => $permission_base + 1
            ),
            array(
                'role_id'       => $role_id_admin,
                'permission_id' => $permission_base + 2
            ),
            array(
                'role_id'       => $role_id_admin,
                'permission_id' => $permission_base + 3
            ),
            /*array(
                'role_id'       => $role_id_person,
                'permission_id' => $permission_base + 6
            ),*/
        );

        DB::table('permission_role')->insert( $permissions );
    }

}