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
}
