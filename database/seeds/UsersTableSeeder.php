<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user1 = User::find(1);
        $user1->name = 'Ben';
        $user1->email = '825048716@qq.com';
        $user1->password = bcrypt('123456');
        $user1->is_admin = true;
        $user1->save();

        $user2 = User::find(2);
        $user2->name = 'Wing';
        $user2->email = '794004527@qq.com';
        $user2->password = bcrypt('123456');
        $user2->is_admin = true;
        $user2->save();
    }
}
