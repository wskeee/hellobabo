<?php

namespace console\controllers;

use common\models\platform\DailyVisitTrend;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SyncWxDataController extends Controller
{

    /**
     * 更新 ACL 访问次数
     */
    public function actionDailyVisitTrend()
    {
        $app = Yii::$app->wechat->miniProgram;
        $data = $app->data_cube->dailyVisitTrend(date("Ymd", strtotime("yesterday")), date("Ymd", strtotime("yesterday")));
        if (count($data['list']) > 0) {
            $data = $data['list'][0];
            //转换日期
            $data['ref_date'] = date("Y-m-d", strtotime($data["ref_date"]));

            $model = DailyVisitTrend::findOne(['ref_date' => $data['ref_date']]);
            if (!$model) {
                $model = new DailyVisitTrend();
            }
            $model->load($data, "");
            $model->save();
        }

        return ExitCode::OK;
    }

}
