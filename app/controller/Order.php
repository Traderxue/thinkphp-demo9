<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Order as OrderModel;
use app\util\Res;

class Order extends BaseController
{
    private $result;
    public function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    public function add(Request $request)
    {
        $postData = $request->post();
        $order = new OrderModel([
            "open_time" => date('Y-m-d H:i:s'),
            "type" => $postData["type"],
            "open_price" => $postData["open_price"],
            "direction" => $postData["direction"],
            "u_id" => 1
        ]);

        $res = $order->save();

        if ($res) {
            return $this->result->success("开仓成功", $order);
        }
        return $this->result->error("开仓失败");
    }

    public function close(Request $request)
    {
        $postData = $request->post();
        $order = OrderModel::where("id", $postData["id"])->find();
        if ($order->direction == 1) {
            $profit = $postData["close_price"] - $order->open_price;
        } else {
            $profit = $order->open_price - $postData["close_price"];
        }
        $res = $order->save([
            "close_price" => $postData["close_price"],
            "profit" => $profit
        ]);
        if ($res) {
            return $this->result->success("平仓成功", $order);
        }
        return $this->result->error("平仓失败");
    }

    public function getByUid($u_id)
    {
        $list = OrderModel::where("u_id", $u_id)->select();
        return $this->result->success("获取数据成功", $list);
    }

    public function page(Request $reqeust)
    {
        $page = $reqeust->param("page");
        $pageSize = $reqeust->param("pageSize");
        $u_id = $reqeust->param("u_id");
        $list = OrderModel::where("u_id", "like", "%{$u_id}%")->paginate([
            "page" => $page,
            "list_rows" => $pageSize
        ]);
        return $this->result->success("获取数据成功", $list);
    }

    public function deleteById($id)
    {
        $res = OrderModel::where("id", $id)->delete();
        if ($res) {
            return $this->result->success("删除数据成功", $res);
        }
        return $this->result->error("删除数据失败");
    }
    
}
