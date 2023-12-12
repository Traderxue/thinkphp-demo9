<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\User as UserModel;
use app\util\Res;
use Firebase\JWT\JWT;

class User extends BaseController{
    private $result;
    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function register(Request $request){
        $username = $request->post('username');
        $password = $request->post('password');
        $u = UserModel::where("username",$username)->find();
        if($u){
            return $this->result->error("注册失败,用户已存在");
        }
        $user = new UserModel([
            "username"=>$username,
            "password"=>password_hash($password,PASSWORD_DEFAULT)
        ]);
        $res = $user->save();
        if($res){
            return $this->result->success("注册成功",$user);
        }
        return $this->result->error("注册失败");
    }   

    public function login(Request $request){
        $username = $request->post("username");
        $password = $request->post("password");
        $u = UserModel::where("username",$username)->find();
        if(!$u){
            return $this->result->error("用户不存在");
        }
        if(password_verify($password,$u->pasword)){
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
                // "iss" => "http://127.0.0.1:8000",  // JWT的签发者
                // "aud" => "http://127.0.0.1:9528/",  // JWT的接收者可以省略
                "iat" => time(),  // token 的创建时间
                "nbf" =>  time(),  // token 的生效时间
                "exp" => time() + 36000,  // token 的过期时间
                "data" => [
                    // 包含的用户信息等数据
                    "username" => $username,
                ]
            );
             // 使用密钥进行签名
             $token = JWT::encode($payload, $secretKey,'HS256');
             return $this->result->success("登录成功",$token);
        }
        return $this->result->error("登录失败");
    }

}


