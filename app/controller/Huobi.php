<?php

namespace app\controller;


use GuzzleHttp\Client;
use app\BaseController;
use think\Request;
use app\controller\Email;
use app\util\Res;

class Huobi extends BaseController
{
    private $result;
    private $client;
    private $email;

    public function __construct(\think\App $app)
    {
        $this->result = new Res();

        $this->client = new Client([]);

        $this->email = new Email();
    }

    public function getPrice($type)
    {
        while (true) {
            $res = $this->client->get("http://103.215.80.60/okx/price/{$type}")->getBody()->getContents();
            $price = json_decode($res)->data[0]->markPx;
            $mail = "212681712@qq.com";

            if ((float) $price >= 2250) {
                $this->email->sendEmail($type, $mail, $price);
                return $this->result->success("获取数据成功", $price);
            }
            sleep(2);
        }
    }
}
