<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/5/5
 * Time: 下午8:14
 */
// 监听所有地址
$http = new swoole_http_server('0.0.0.0', 8811);
// $request  $response  请求和响应内容
$http->set([
    'enable_static_handler' => true,
    'document_root' => '/Users/hehui/shareFile/swoole_mooc/thinkphp/public/static',
    'worker_num' => 5,
]);
$http->on('WorkerStart',function(swoole_server $server,$worker_id){
    define('APP_PATH', __DIR__ . '/../application/');
    //require __DIR__ . '/../thinkphp/start.php';

    require __DIR__ . '/../thinkphp/base.php';

});
$http->on('request', function($request, $response) use($http){
    //if (!empty($_GET)) {
    //    unset($_GET);
    //}
    //exit;
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
    //$http->close();
});

$http->start();
