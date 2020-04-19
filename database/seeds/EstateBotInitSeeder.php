<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use SmaaT\EstateBot\Enum;
use SmaaT\EstateBot\Models\EstateBotProvider;

class EstateBotInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create_account();
        $this->create_providers();
        $this->create_permission();
    }

    public function create_permission()
    {
        $permission = \App\Permission::create([
            'name' => 'manage-estate-bot',
            'display_name' => 'مدیریت ربات ثبت ملک',
            'description' => 'امکان مدیریت تنظیمات و مشاهده گزارشات ربات',
        ])->id;

        Role::whereName('owner')->first()->attachPermission($permission);

        $this->command->info("The manage permission of bot was created :)");
    }

    public function create_providers()
    {
        EstateBotProvider::create([
            'provider' => Enum::MaskanFile,
            'username' => 'محمدی',
            'password' => '09389014517',
            'base_url' => 'http://www.maskan-file.ir/',
        ]);

        EstateBotProvider::create([
            'provider' => Enum::MelkeIrani,
            'username' => '09389014517',
            'password' => '102030',
            'base_url' => 'http://www.melkeirani.com/',
        ]);

        $this->command->info("The bot providers was created :)");
    }

    public function create_account()
    {
        $user = User::create([
            'first_name' => "ربات",
            'last_name' => "ثبت ملک",
            'phone_number' => "09123456789",
            'username' => "robot",
            'email' => "robot@maskanshow.ir",
            'password' => bcrypt("svsvsl;kjfpwier;lnb;lfbs;fdgjsldfkgjssf;"),
            'is_public_info' => false,
        ]);

        $user->id = "robot";
        $user->save();

        $user->attachRole("owner");

        $this->command->info("The robot account was created :)");
    }
}
