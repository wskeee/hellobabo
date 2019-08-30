<?php

namespace console\controllers;

use common\models\platform\Withdrawals;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * 用户定时任务
 *
 * @author Administrator
 */
class UserController extends Controller {

    /**
     * 提现付款
     * 
     */
    public function actionWithdrawalPay() {
        //
        //do something
        //
        //取出一批已经审核通过的提现申请进行付款
        $withdrawals = Withdrawals::find()
                ->where(['status' => Withdrawals::STATUS_CHECK_SUCCESS])
                ->orderBy(['created_at' => SORT_ASC])
                ->limit(20)
                ->all();

        /* @var $withdrawal Withdrawals */
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->pay();
        }
        
        return ExitCode::OK;
    }

}
