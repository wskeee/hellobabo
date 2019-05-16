<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Withdrawals;
use common\models\system\Config;
use common\models\User;
use Yii;

class WithdrawalReady extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        /* @var $user User */
        $user = Yii::$app->user->identity;
        //对应商家
        if (!$user->moneyVerification()) {
            return new Response(Response::CODE_USER_MONEY_VERIFICATION_FAILED);
        }

        $need_check_config = Config::findOne(['config_name' => 'user_withdrawal_need_check']);
        $once_max_config = Config::findOne(['config_name' => 'user_withdrawal_once_max']);
        $day_max_config = Config::findOne(['config_name' => 'user_withdrawal_day_max']);
        $need_check = intval($need_check_config->config_value);
        $once_max_money = floatval($once_max_config->config_value);
        $day_max_money = floatval($day_max_config->config_value);

        //查出当前已经申请的金额
        $hasDones = Withdrawals::find()
                ->select(['ifnull(sum(amount),0) as amount'])
               ->where([
                    'user_id' => $user->id,
                    'status' => [Withdrawals::STATUS_APPLYING, Withdrawals::STATUS_CHECK_SUCCESS, Withdrawals::STATUS_PAY_SUCCESS]]
                )
                ->andWhere(['between', 'created_at', strtotime('today'), strtotime('today +1 day')])
                ->column();
        
        return new Response(Response::CODE_COMMON_OK, null, [
            'has_apply_money' => $hasDones[0], //今天已经申请的金额
            'newest_money' => $user->money, //当前余额
            'need_check' => $need_check, //是否需要审核
            'once_max_money' => $once_max_money, //单笔最大金额
            'day_max_money' => $day_max_money, //一天最大金额
        ]);
    }

}
