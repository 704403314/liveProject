<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/12
 * Time: 下午9:45
 */
class Http
{
    const HOST = '0.0.0.0';
    const PORT = 8811;
    public $http = null;
    public function __construct()
    {
        $this->http = new swoole_http_server(self::HOST, self::PORT);
        $this->http->set([
            'worker_num' => 4,
            'task_worker_num' => 4,
            'document_root' => '/Users/hehui/shareFile/swoole_mooc/thinkphp/public/static',
            'enable_static_handler' => true,
        ]);
        $this->http->on('workerStart', [$this, 'onWorkerStart']);
        $this->http->on('request', [$this, 'onRequest']);
        $this->http->on('close', [$this, 'onClose']);
        $this->http->on('task', [$this, 'onTask']);
        $this->http->on('finish', [$this, 'onFinish']);
        $this->http->start();
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
        $_POST['http_server'] = $this->http;
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
        echo 'clientid:'. $frame . "\n";
    }


    public function onTask($serv,$taskId,$workerId,$data)
    {
        // 分发任务机制 不同任务  走不同逻辑
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $obj->$method($data['data']);
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

new Http();