<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Bill as BillModel;
use app\util\Res;

class Bill extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $postData = $request->post();
        $bill = new BillModel([
            "time"=>date("Y-m-d H:i:s"),
            "money"=>$postData["money"],
            "operate"=>$postData["operate"],
            "u_id"=>$postData["u-id"]
        ]);
        $res = $bill->save();
        if($res){
            return $this->result->success("添加数据成功",$bill);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request){
        $post = $request->post();
        $bill = BillModel::where("id",$post["id"])->find();
        $bill->save([
            "money"=>$post["money"],
            "operate"=>$post["operate"]
        ]);
        $res = $bill->save();
        if($res){
            return $this->result->success("编辑数据成功",$post);
        }   
        return $this->result->error("编辑数据失败");
    }

    public function delete($id){
        $res = BillModel::where("id",$id)->delete();
        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }

    public function getByUid($u_id){
        $list = BillModel::where("u-id",$u_id)->select();
        return $this->result->success("获取数据成功",$list);
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $u_id = $request->param("u_id");
        
        $list = BillModel::where("u_id","like","%{$u_id}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }
}