<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/6/29
 * Time: 上午11:06
 */

class  Server {
    const PORT = 8811;

    public function port()
    {
        //$shell = "df -h | awk '{print $3;}'";
        //$res = shell_exec($shell);
        //var_dump(explode(PHP_EOL,$res));exit;


        // linux下命令
        // 2>/dev/null 去掉无用字符
        $shell = "netstat -anp 2>/dev/null | grep " . self::PORT . ' | grep LISTEN | wc -l';
        // mac下命令
        //$shell = "lsof -i:" . self::PORT . ' | grep LISTEN|wc -l';
        $res = shell_exec($shell);
        if ($res != 1) {
            echo date('Y-m-d H:i:s').'error'.PHP_EOL;
        } else {
            echo date('Y-m-d H:i:s').'success'.PHP_EOL;
        }
    }
}
swoole_timer_tick(2000,function($timer_id){
    (new Server())->port();
    echo 'timer_start'.PHP_EOL;
});
