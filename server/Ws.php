<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/12
 * Time: 下午9:45
 */
class Ws
{
    const HOST = '0.0.0.0';
    //const PORT = 80;
    const PORT = 8811;
    const CHART_PORT = 8812;
    public $http = null;
    public $ws = null;
    public function __construct()
    {

        // 清空redis 值 ,比如保存的用户连接id有序集合
        //define('APP_PATH', __DIR__ . '/../application/');
        //
        ////require __DIR__ . '/../thinkphp/base.php';
        //require __DIR__ . '/../thinkphp/start.php';
        //try{
        //    think\Container::get('app', [APP_PATH])
        //        ->run()
        //        ->send();
        //} catch (\Exception $e) {
        //
        //}
        //$clients = \app\common\lib\redis\Predis::getInstance()->sMembers(config('redis.live_game_key'));
        //if (!empty($clients)) {
        //    foreach ($clients as $v) {
        //        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$v);
        //
        //    }
        //}
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->listen(self::HOST,self::CHART_PORT, SWOOLE_SOCK_TCP);
        $this->ws->set([
            'worker_num' => 4,
            'task_worker_num' => 4,
            'document_root' => '/Users/hehui/shareFile/swoole_mooc/thinkphp/public/static',
            //'document_root' => '/data/web_data/alpha_framework/apps/web/public/static',
            'enable_static_handler' => true,
        ]);
        $this->ws->on('request', [$this, 'onRequest']);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('workerStart', [$this, 'onWorkerStart']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
        $this->ws->start();
    }
    public function onOpen($ws,$request)
    {
        //$this->ws->push($request->fd,'testtest');
        \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'),$request->fd);
        //print_r($ws);
    }
    public function onMessage($ws,$frame)
    {
        echo 'push-message:'. $frame->data . "\n";
        //$data = ['task' => 1, 'fd' => $frame->fd];
        //$ws->task($data);
        $ws->push($frame->fd, 'server-push:' . date('Y-m-d H:i:s'));
    }

    public function onWorkerStart($server,$worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');

        //require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';

    }

    public function onRequest($request,$response)
    {
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_FILES = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }

        $_POST['http_server'] = $this->ws;
        ob_start();
        try{
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        } catch (\Exception $e) {

        }
        //echo request()->action().
        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }


    public function onClose($ws,$frame)
    {
        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$frame);

        echo 'clientid:'. $frame . "\n";
    }


    public function onTask($serv,$taskId,$workerId,$data)
    {
        // 分发任务机制 不同任务  走不同逻辑
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $obj->$method($data['data'],$serv);
        //try{
        //    $sms = \app\common\lib\sms\api_demo\SmsDemo::sendSms($data['phone'],$data['code']);
        //
        //} catch (\Exception $e) {
        //    print_r($e->getMessage());
        //    return Util::show(config('code.error'),'阿里大鱼异常');
        //}
        return true;

    }
    public function onFinish($serv,$taskId,$data)
    {
        echo "taskID:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }
}

new Ws();