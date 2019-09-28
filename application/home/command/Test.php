<?php
/**
 * Created by PhpStorm.
 * User: hehui
 * Date: 2019/7/31
 * Time: 下午4:18
 */
namespace app\home\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\common\model\LiveTeamtemp;
class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')->setDescription('Here is the remark ');
    }

    protected function execute(Input $input, Output $output)
    {
        for ($i=0;$i<1000000;$i++) {
            try {
                $model = new LiveTeamtemp();
                $model->create_time = time();
                $model->update_time = time();
                $model->image = '';
                $model->type = 0;
                $model->name = rand(1000,9999);

                $model->save();
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
                break;
            }
        }


        $output->writeln("TestCommand:");

    }
}