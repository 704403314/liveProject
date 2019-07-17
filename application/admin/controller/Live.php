<?php
namespace app\admin\controller;
use app\index\controller\Util;
use app\common\lib\redis\Predis;
//use think\Db;
//use think\Model;
class Live
{
    const  PROJECT_PATH = '/Users/hehui/shareFile/swoole_mooc/thinkphp';
    public $table_live_outs = 'live_outs';

    public function push()
    {
        if (empty($_POST)) {
           return Util::show(config('code.error'),'error');
        }
        $ws = $_POST['http_server'];
        //print_r($ws);
        // 赛况信息入库
        //$db = new Db();
        //try{
        //    $res = $db::table($this->table_live_outs)->where('id',1)->find();
        //
        //} catch (\Exception $e) {
        //    print_r($e->getMessage());
        //}
        //print_r($db);
        // push 到用户
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ]
        ];
        $data = [
            'type' => intval($_POST['type']),
            'title' => !empty($teams[$_POST['team_id']]['name']) ? $teams[$_POST['team_id']]['name']: '直播员',
            'logo' => !empty($teams[$_POST['team_id']]['logo']) ?$teams[$_POST['team_id']]['logo']: '',
            'content' => !empty($_POST['content']) ?$_POST['content']: '',
            'image' => !empty($_POST['image']) ?$_POST['image']: ''
        ];


        $taskData = [
            'method' => 'pushLive',
            'data' => $data,
            'ws' => $ws

        ];
        try{
            $_POST['http_server']->task($taskData);

        } catch (\Exception $e) {

        }
        return Util::show(config('code.success'),'ok');


    }


}
