<?php
class UsersSeed
extends DatabaseSeeder
{
    public function run()
    {
        $users = [
            [
                "username" => "lukas",
                "password" => Hash::make("oberon"),
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