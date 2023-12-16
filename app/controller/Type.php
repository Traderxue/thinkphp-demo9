<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Type as TypeModel;
use app\util\Res;

class Type extends BaseController
{
    private $result;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $post = $request->post();

        $t = TypeModel::where("type", $post["type"])->find();

        if ($t) {
            return $this->result->error("种类已存在");
        }

        $type = new TypeModel([
            "add_time" => date("Y-m-d H:i:s"),
            "type" => $post["type"],
        ]);
        $res = $type->save();
        if ($res) {
            return $this->result->success("添加数据成功", $type);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request)
    {
        $post = $request->post();
        $type = TypeModel::where("type", $post["type"])->find();

        $res = $type->save([
            "parcent" => $post["parcent"],
            "price" => $post["price"],
            "volume" => $post["volume"]
        ]);

        if ($res) {
            return $this->result->success("编辑数据成功", $type);
        }
        return $this->result->error("数据编辑失败");
    }

    public function page(Request $request)
    {
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $type = $request->param("type");

        $list = TypeModel::where("id", "like", "%{$type}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }

    public function deleteById($id){
        $res = TypeModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("数据删除成功",$res);
        }
        return $this->result->error("数据删除失败");
    }

    public function getDetail($type){
        $type = TypeModel::where("type",$type)->find();
        if(!$type){
            return $this->result->error("没有查询到数据");
        }
        return $this->result->success("获取数据成功",$type);
    }
}
