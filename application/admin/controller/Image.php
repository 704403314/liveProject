<?php
namespace app\admin\controller;
use app\index\controller\Util;
class Image
{
    const  PROJECT_PATH = '/Users/hehui/shareFile/swoole_mooc/thinkphp';


    public function index()
    {
        if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg"))
            && ($_FILES["file"]["size"] < 2000000)) {

            if ($_FILES["file"]["error"] > 0) {
                return Util::show(config('code.error'),'上传文件出错');
            } else {
                $projectPath = self::PROJECT_PATH;
                $name = $_FILES["file"]["name"];
                //print_r(strrpos($name,'.'));
                $ext  = substr($name , strrpos($name,'.'));
                $_FILES["file"]["name"] = md5_file($_FILES["file"]["tmp_name"]) . $ext;
                if (file_exists($projectPath."/public/static/upload/" . $_FILES["file"]["name"])) {
                    //echo $_FILES["file"]["name"] . " already exists. ";
                } else {
                    move_uploaded_file($_FILES["file"]["tmp_name"], $projectPath."/public/static/upload/" . $_FILES["file"]["name"]);
                }
                $data = config('live.host')."/upload/" . $_FILES["file"]["name"];
                return Util::show(config('code.success'),'OK',$data);
            }

        } else {
            return Util::show(config('code.error'),'文件格式非法');
        }


        //print_r($_FILES["file"]);
        //echo '999111';
        //$file = request()->file('file');
        //$info = $file->move('../public/static/upload');
        //$info = move_uploaded_file($_FILES["file"]["tmp_name"],config('live.host').'/public/static/upload/'.$_FILES["file"]["name"]);
        //if ($info) {
        //    $data = [
        //        'image' => config('live.host') . '/upload/' . $_FILES["file"]["name"],
        //    ];
        //    return Util::show(config('code.success'),'OK',$data);
        //} else {
        //    return Util::show(config('code.error'),'error');
        //
        //}
        //print_r($info);
    }


}
