<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/20
 * Time: 上午10:34
 */
namespace app\admin\logic;
use app\common\BaseLogic;
use app\common\model\LiveOuts;

//use app\common\model\LiveTeam;
class LogicLiveOuts extends BaseLogic {

    public function pushMessage($ws,$data)
    {
        //$teams = [
        //    1 => [
        //        'name' => '马刺',
        //        'logo' => '/live/imgs/team1.png',
        //    ],
        //    4 => [
        //        'name' => '火箭',
        //        'logo' => '/live/imgs/team2.png',
        //    ]
        //];'
        $teams = \app\admin\logic\LogicTeam::getInstance()->getList();
        if (empty($_POST['content'])) {
            $_POST['content'] = '';
        }
        if (empty($_POST['image'])) {
            $_POST['image'] = '';
        }
        $data = [
            'type' => intval($_POST['type']),
            'title' => !empty($teams[$_POST['team_id']]['name']) ? $teams[$_POST['team_id']]['name']: '直播员',
            'logo' => !empty($teams[$_POST['team_id']]['image']) ?$teams[$_POST['team_id']]['image']: '',
            'content' => !empty($_POST['content']) ?$_POST['content']: '',
            'image' => !empty($_POST['image']) ?$_POST['image']: ''
        ];

        $taskData = [
            'method' => 'pushLive',
            'data' => $data,
            'ws' => $ws
        ];
        try{
            $ws->task($taskData);
        } catch (\Exception $e) {
            //print_r($e->getMessage());
            //return ['code'=>1, 'msg'=>'推送失败'];
        }
        // 保存推送数据
        $model = new LiveOuts();
        $model->create_time = time();
        $model->type = $_POST['type'];
        $model->image = $_POST['image'];
        $model->content = $_POST['content'];
        $model->team_id = $_POST['team_id'];
        $model->game_id = $_POST['game_id'];
        $model->save();
        return  ['code' => 0];

    }
}