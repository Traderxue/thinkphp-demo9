<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\User as UserModel;
use app\util\Res;
use Firebase\JWT\JWT;

class User extends BaseController
{
    private $result;
    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function register(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $u = UserModel::where("username", $username)->find();
        if ($u) {
            return $this->result->error("注册失败,用户已存在");
        }
        $user = new UserModel([
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
        $res = $user->save();
        if ($res) {
            return $this->result->success("注册成功", $user);
        }
        return $this->result->error("注册失败");
    }

    public function login(Request $request)
    {
        $username = $request->post("username");
        $password = $request->post("password");
        $u = UserModel::where("username", $username)->find();
        if (!$u) {
            return $this->result->error("用户不存在");
        }
        if (password_verify($password, $u->password)) {
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
            $token = JWT::encode($payload, $secretKey, 'HS256');
            return $this->result->success("登录成功", $token);
        }
        return $this->result->error("登录失败");
    }

    public function verify(Request $request)
    {
        $postData = $request->post();
        $user = UserModel::where("id", $postData['id'])->find();
        $res = $user->save([
            "name" => $postData["name"],
            "phone" => $postData["phone"],
            "id_card" => $postData["id_card"]
        ]);
        if ($res) {
            return $this->result->success("提交成功,等待审核", $user);
        }
        return $this->result->error("提交失败,请稍后重试");
    }

    public function isVerify($id)
    {
        $user = UserModel::where("id", $id)->find();
        $res = $user->save([
            'verify' => 1
        ]);
        if ($res) {
            return $this->result->success("已通过验证", $user);
        }
        return $this->result->error("验证失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page", 1);
        $pageSize = $request->param("pageSize", 10);
        $username = $request->param("username");

        $list = UserModel::where("username", "like", "%{$username}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }

    public function deleteById($id)
    {
        $res = UserModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除成功", $res);
        }
        return $this->result->error("删除失败");
    }

    public function putup(Request $request)
    {
        $user = UserModel::where("id", $request->post("id"))->find();
        $res = $user->save(["balance" => $request->post("money")]);
        if ($res) {
            return $this->result->success("充值成功", $res);
        }
        return $this->result->error("充值失败");
    }

    public function edit(Request $request){
        $nickname = $request->post("nickname");
        $avator = $request->post("avator");

        $user = UserModel::where("id",$request->post("id"))->find();
        $res = $user->save([
            "nickname"=>$nickname,
            "avator"=>$avator
        ]);
        if($res){
           return $this->result->success("编辑资料成功",$user);
        }
        return $this->result->error("编辑资料失败");

    }
}
