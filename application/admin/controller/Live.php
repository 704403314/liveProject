<?php
namespace app\admin\controller;
use app\common\controller\Util;
use app\admin\controller\BaseController;
class Live extends BaseController
{
    public $table_live_outs = 'live_outs';

    public function __construct()
    {
        $this->model = \app\admin\logic\LogicLiveOuts::getInstance();
    }

    public function push()
    {
        $field = ['type' => '第几节', 'team_id' => '球队'];
        $check = $this->checkEmpty($field,$_POST);

        if ($check['code'] != 0) {
            return Util::show(config('code.error'),$check['msg']);
        }

        $ws = $_POST['http_server'];
        $res = $this->model->pushMessage($ws,$_POST);
        if ($res['code'] == 0) {
            return Util::show(config('code.success'),'ok');
        } else {
            return Util::show(config('code.error'),$res['msg']);
        }

    }


}
