<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Admin as AdminModel;
use app\util\Res;
use Firebase\JWT\JWT;

class Admin extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $a = AdminModel::where("username", $post["username"])->find();

        if ($a) {
            return $this->result->error("添加失败,用户已存在");
        }

        $admin = new AdminModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT),
            "nickname" => $post["nickname"],
            "add_time" => date("Y-m-d H:i:s")
        ]);

        $res = $admin->save();
        if ($res) {
            return $this->result->success("用户添加成功", $admin);
        }
        return $this->result->error("用户添加失败");
    }


    public function login(Request $request){
        $username = $request->post("username");
        $password = $request->post("password");
        $admin = AdminModel::where("username",$username)->find();
        if(!$admin){
            return $this->result->error("用户不存在");
        }
        if(password_verify($password,$admin->password)){
            $secretKey = '123456789'; // 用于签名令牌的密钥，请更改为安全的密钥

            $payload = array(
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
            return $this->result->success("登录成功",$token);
        }
        return $this->result->error("登录失败");
    }


    public function edit(Request $request)
    {
        $post = $request->post();
        $admin = AdminModel::where("username", $post["username"])->find();
        $res = $admin->save([
            "nickname" => $post["nickname"],
            "permission" => $post["permission"]
        ]);

        if ($res) {
            return $this->result->success("编辑数据成功", $admin);
        }
        return $this->result->error("编辑数据失败");
    }

    public function delete($id)
    {
        $res = AdminModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("数据删除失败");
    }

    public function getByid($id)
    {
        $admin = AdminModel::where("id", $id)->find();
        return $this->result->success("获取用户信息成功", $admin);
    }

    public function page(Request $request)
    {
        $page = $request->param["page"];
        $pageSize = $request->param["pageSize"];
        $username = $request->param["username"];
        $list = AdminModel::where("username", "like", $username)->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }
}
