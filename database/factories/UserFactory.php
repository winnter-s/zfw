<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

// 生成模拟数据的工厂格式
$factory->define(User::class, function (Faker $faker) {
    return [
        'username'=> $faker->userName,
        'truename'=> $faker->name(),
        // laravel 中的 bcrypt 是 php5 中的 password_hash() 函数别名
        // password_verify 是验证
        'password'=>bcrypt('123456'), // 生成密码长度 64-255 之间
        'phone' => $faker->phoneNumber,
        'sex' => ['先生','女士'][rand(0,1)]
    ];
});
