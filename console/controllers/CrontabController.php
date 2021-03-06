<?php

namespace console\controllers;

use common\models\system\Crontab;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * 定时任务
 *
 * @author Administrator
 */
class CrontabController extends Controller {

    /**
     * 定时任务入口
     * @return int Exit code
     */
    public function actionIndex() {
        $crontab = Crontab::findAll(['is_del' => 0]);
        $tasks = [];

        foreach ($crontab as $task) {

            // 第一次运行,先计算下次运行时间
            if (!$task->next_rundate) {
                $task->next_rundate = $task->getNextRunDate();
                $task->save(false);
                continue;
            }

            // 判断运行时间到了没
            if ($task->next_rundate <= date('Y-m-d H:i:s')) {
                $tasks[] = $task;
            }
        }

        $this->executeTask($tasks);

        return ExitCode::OK;
    }

    /**
     * @param  array $tasks 任务列表
     * @author jlb
     */
    public function executeTask(array $tasks) {

        $pool = [];
        $startExectime = $this->getCurrentTime();
        $php_path = Yii::$app->params['php_path'];
        $yii_path = dirname(dirname(__DIR__)).'/yii';
      
        foreach ($tasks as $task) {
            $pool[] = proc_open("$php_path $yii_path $task->route", [], $pipe);
        }

        // 回收子进程
        while (count($pool)) {
            foreach ($pool as $i => $result) {
                $etat = proc_get_status($result);
                if ($etat['running'] == FALSE) {
                    proc_close($result);
                    unset($pool[$i]);
                    # 记录任务状态
                    $tasks[$i]->exec_time = round($this->getCurrentTime() - $startExectime, 2);
                    $tasks[$i]->last_rundate = date('Y-m-d H:i');
                    $tasks[$i]->next_rundate = $tasks[$i]->getNextRunDate();
                    $tasks[$i]->status = 0;
                    // 任务出错
                    if ($etat['exitcode'] !== ExitCode::OK) {
                        $tasks[$i]->status = 1;
                    }

                    $tasks[$i]->save(false);
                }
            }
        }
    }

    private function getCurrentTime() {
        list ($msec, $sec) = explode(" ", microtime());
        return (float) $msec + (float) $sec;
    }

}
