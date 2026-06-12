<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

use App\Model\Permission\Role;
use App\Model\Permission\User;
use Hyperf\Database\Seeders\Seeder;

class UserSeeder20240926 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $entity = User::firstOrCreate([
            'email' => 'admin@adminmine.com',
        ], [
            'username' => 'admin',
            'user_type' => '100',
            'nickname' => '创始人',
            'email' => 'admin@adminmine.com',
            'phone' => '16858888988',
            'signed' => '广阔天地，大有所为',
            'created_by' => 0,
            'updated_by' => 0,
            'status' => 1
        ]);
        $role = Role::firstOrCreate([

            'code' => 'SuperAdmin',
        ], [
            'name' => '超级管理员',

        ]);
        $entity->roles()->sync($role);
    }
}
