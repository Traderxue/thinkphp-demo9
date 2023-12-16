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

Route::group("/admin",function(){

    Route::post("/add","admin/add");

    Route::post("/login","admin/login");

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

Route::group("/huobi",function(){

    Route::get("/detail/:type","huobi/getDetail");

    Route::get("/depath/:type","huobi/getDepath");

});


Route::group("/order",function(){

    Route::post("/add","order/add");

    Route::post("/close","order/close");

    Route::get("/getuid/:u_id","order/getByUid");

    Route::get("/page","order/page");

    Route::delete("/delete/:id","order/deleteById");

})->middleware(app\middleware\JwtMiddleware::class);


Route::group("/bill",function(){

    Route::post("/add","bill/add");

    Route::post("/edit","bill/edit");

    Route::delete("/delete/:id","bill/delete");

    Route::get("/getbyuid/:u_id","bill/getByUid");

    Route::get("/page","bill/page");
})->middleware(app\middleware\JwtMiddleware::class);


Route::group("/admin",function(){

    Route::edit("/edit","admin/edit");

    Route::delete("/delete/:id","admin/delete");

    Route::get("/get/:id","admin/getByid");

    Route::get("/page","admin/page");

})->middleware(app\middleware\JwtMiddleware::class);

Route::group("/type",function(){

    Route::post("/add","type/add");

    Route::post("/edit","type/edit");

    Route::get("/page","type/page");

    Route::delete("/delete/:id","type/deleteById");

    Route::get("/get/:type","type/getDetail");

})->middleware(app\middleware\JwtMiddleware::class);