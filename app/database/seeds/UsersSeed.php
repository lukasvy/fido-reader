<?php
class UsersSeed
extends DatabaseSeeder
{
    public function run()
    {
        $users = [
            [
                "username" => "admin",
                "password" => Hash::make("test"),
                "email"    => "lukas.vyslocky@gmail.com",
                "role"	   => "admin",
                "active"   => "true",
            ]
        ];
        foreach ($users as $user)
        {
            User::create($user);
        }
    }
}