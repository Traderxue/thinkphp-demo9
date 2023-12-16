<?php

namespace app\controller;


use GuzzleHttp\Client;
use app\BaseController;
use think\Request;
use app\util\Res;

class Huobi extends BaseController
{
    private $result;
    private $client;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();

        $this->client = new Client([
            "proxy" => "http://127.0.0.1:23457",
            "verify" => false
        ]);
    }

    public function getDetail($type)        //种类,最高价，最低价，成交量，涨跌幅，最新价
    {
        $res = $this->client->get("https://api.huobi.pro/market/detail?symbol={$type}usdt")->getBody()->getContents();

        $data = json_decode($res);

        $high = $data->tick->high;

        $low = $data->tick->low;

        $volume = $data->tick->vol;

        $latest = $data->tick->close;

        $parcent = number_format((($data->tick->close - $data->tick->open) / $data->tick->open) * 100, 2);

        if ($parcent > 0) {
            $up = 1;
        } else {
            $up = 0;
        }


        $tick = [
            "type" => $type,
            "high" => $high,
            "low" => $low,
            "volume" => $volume,
            "latest" => $latest,
            "parcent" => $parcent,
            "up" => $up
        ];

        return $this->result->success("获取数据成功", $tick);
    }

    public function getDepath($type){
        $res = $this->client->get("https://api.huobi.pro/market/depth?symbol={$type}usdt&depth=5&type=step0")->getBody()->getContents();
        $data = json_decode($res);
        $bids = $data->tick->bids;
        $asks = $data->tick->asks;

        $tick = [
            "bids"=>$bids,
            "askd"=>$asks
        ];

        return $this->result->success("获取数据成功",$tick);
    }
}
