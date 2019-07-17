<?php
namespace app\index\controller;
use app\index\controller\Util;
use app\common\lib\redis\Predis;
class Chart
{
    public function index()
    {
        // 登录判断

        if (empty($_POST['game_id'])) {
            return Util::show(config('code.error'),'error');
        }
        if (empty($_POST['content'])) {
            return Util::show(config('code.error'),'error');
        }

        $data = [
            'user' => "用户" . rand(0,2000),
            'content' => $_POST['content'],
        ];
        //echo '999';
        //$ws = $_POST['http_server'];
        //print_r($ws->ports[1]->connnections);
        foreach ($_POST['http_server']->ports[1]->connnections as $fd) {
            $_POST['http_server']->push($fd,'123');
            //$_POST['http_server']->push(2,'123');
            //$_POST['http_server']->push(3,'123');
            //$_POST['http_server']->push(4,'123');
            //$_POST['http_server']->push(5,'123');
            //$_POST['http_server']->push(6,'123');
            //$_POST['http_server']->push(7,'123');
            //$_POST['http_server']->push(8,'123');
            //$_POST['http_server']->push(9,'123');
            //$_POST['http_server']->push(10,'123');
        }
        return Util::show(config('code.success'),'ok', json_encode($data));

    }

}
