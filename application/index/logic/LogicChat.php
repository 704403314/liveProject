<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 下午8:06
 */
namespace app\index\logic;

use app\common\BaseLogic;
use app\common\model\LiveChart;

class LogicChat extends BaseLogic
{

    public function pushChat($userInfo=[])
    {
        $data = [
            'user' => "用户" . $userInfo['user'],
            'content' => $_POST['content'],
        ];

        $ws = $_POST['http_server'];

        $taskData = [
            'method' => 'pushChat',
            'data' => $data,
            'ws' => $ws
        ];
        try{
            $ws->task($taskData);
        } catch (\Exception $e) {

        }
        // 保存评论到数据库
        $model = new LiveChart();
        $model->create_time = time();
        $model->content = $_POST['content'];
        $model->game_id = $_POST['game_id'];
        $model->user_phone = $userInfo['user'];
        //$model->user_id = $_POST['game_id'];
        $res = $model->save();
        //print_r($res);
        return  ['code' => 0];
    }
}