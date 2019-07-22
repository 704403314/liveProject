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

        // i清空redis 值 ,比如保存的用户连接id有序集合111992
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
            //'document_root' => '/Users/hehui/shareFile/swoole_mooc/swoole_live/public/static',
            'document_root' => '/data/web_data/apps/liveProject/public/static',
            'enable_static_handler' => true,
        ]);
        $this->ws->on('request', [$this, 'onRequest']);

        $this->ws->on('open', [$this, 'onOpen']);
        $this->ws->on('start', [$this, 'onStart']);
        $this->ws->on('message', [$this, 'onMessage']);
        $this->ws->on('workerStart', [$this, 'onWorkerStart']);
        $this->ws->on('close', [$this, 'onClose']);
        $this->ws->on('task', [$this, 'onTask']);
        $this->ws->on('finish', [$this, 'onFinish']);
        $this->ws->start();
    }

    /**
     * 客户和服务端建立连接时
     * @param $ws
     * @param $request
     */
    public function onOpen($ws,$request)
    {
        if ($request->server['server_port'] == '8811') {
            \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'),$request->fd);
        }
        if ($request->server['server_port'] == '8812') {
            \app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.chat_key'),$request->fd);
        }
    }

    public function onStart()
    {
        swoole_set_process_name('live_master');
    }
    public function onMessage($ws,$frame)
    {
        //echo 'push-message:'. $frame->data . "\n";
        //$data = ['task' => 1, 'fd' => $frame->fd];
        //$ws->task($data);
        $ws->push($frame->fd, 'server-push:' . date('Y-m-d H:i:s'));
    }

    public function onWorkerStart($server,$worker_id)
    {
        define('APP_PATH', __DIR__ . '/../../../application/');
        require __DIR__ . '/../../../thinkphp/start.php';

        $redisConfig = config('redis.game_key');
        foreach ($redisConfig as $v) {
            if (strpos($v,'_key') !== false) {
                $clients = \app\common\lib\redis\Predis::getInstance()->sMembers($v);
                //print_r($clients);
                if (!empty($clients)) {
                    foreach ($clients as $fd) {
                        \app\common\lib\redis\Predis::getInstance()->sRem($v,$fd);
                    }
                }

            }
        }


    }

    public function onRequest($request,$response)
    {
        //print_r($request);
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

        if (isset($request->cookie)) {
            foreach ($request->cookie as $k => $v) {
                $_COOKIE[$k] = $v;
            }
        }

        if ($_SERVER['REQUEST_URI'] != '/favicon.ico') {
            $this->writeLog();
        } else {
            $response->status(404);
            $response->end();
            return ;
        }
        //print_r($request->cookie);

        $_POST['http_server'] = $this->ws;
        //ob_start();

        ob_start();

        try{
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        } catch (\Exception $e) {
            print_r($e->getMessage(). 'error123');
        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }


    public function onClose($ws,$frame)
    {
        //print_r($ws);
        //print_r($frame);
        //\app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'),$frame);

        //echo 'clientid:'. $frame . PHP_EOL;
    }


    public function onTask($serv,$taskId,$workerId,$data)
    {
        // 分发任务机制 不同任务  走不同逻辑
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $obj->$method($data['data'],$serv);

        return true;

    }
    public function onFinish($serv,$taskId,$data)
    {
        //echo "taskID:{$taskId}\n";
        //echo "finish-data-success:{$data}\n";
    }

    public function writeLog()
    {
        $datas = array_merge(['date' => date('Y-m-d H:i:s')],$_GET,$_POST,$_SERVER);
        $logs = '';
        foreach ($datas as $key=>$val) {
            $logs .= $key . ' : ' . $val . ' ';
        }
        //echo $logs;
        //swoole_async_writefile(APP_PATH . '../runtime/log/' . date('Ym') . '/' . date('d') . 'access.log',
        //    $logs . PHP_EOL, function($filename){
        //
        //    },FILE_APPEND);
        file_put_contents(APP_PATH . '../runtime/log/' . date('Ym') . '/' . date('d') . '_access.log',$logs . PHP_EOL,FILE_APPEND);

    }
}

new Ws();

// 几个信号源：sigterm=》用于停止服务器 sigusr1=》重启work 进程  usr2 重启task进程
