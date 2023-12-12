<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;



Route::group("/user", function () {

    Route::post("/register", "user/register");

    Route::post("/login", "user/login");

});

Route::group("/user", function () {

    Route::post("/verify","user/verify");

    Route::get("/isverify/:id","user/isVerify");

    Route::get("/page","user/page");

    Route::delete("/delete/:id","user/delete");

    Route::post("/putup","user/putup");
    
    Route::post("/upload","file/index");

    Route::post("/edit","user/edit");

})->middleware(app\middleware\JwtMiddleware::class);