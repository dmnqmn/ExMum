<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    const userInfoList = [
        ['email' => 'super_admin@exmum.com', 'password' => 'super_admin', 'role' => \General::USER_ROLES['SUPER']],
        ['email' => 'admin@exmum.com', 'password' => 'admin', 'role' => \General::USER_ROLES['ADMIN']],
        ['email' => 'test@exmum.com', 'password' => 'test', 'role' => \General::USER_ROLES['NORMAL']]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::userInfoList as $userInfo) {
            $user = User::create($userInfo['email'], $userInfo['password'], $userInfo['role']);
            $user->status = 1;
            $user->save();
        }
    }
}
