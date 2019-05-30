<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * 系统定时任务
 *
 * @author Administrator
 */
class SystemController extends Controller
{

    /**
     * 删除所有临时下载文件
     */
    public function actionClearTempDownload()
    {
        //设置需要删除的文件夹
        $path = \Yii::getAlias('@backend/web/upload/download/*');
        //调用函数，传入路径
        $this->deldir($path);
        return ExitCode::OK;
    }

    //清空文件夹函数和清空文件夹后删除空文件夹函数的处理
    private function deldir($path)
    {
        $files = glob($path);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

}
