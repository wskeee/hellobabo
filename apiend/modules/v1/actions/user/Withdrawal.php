<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Withdrawals;
use common\models\system\Config;
use common\models\User;
use Yii;

class Withdrawal extends BaseAction {

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
        //本次提现金额
        $money = floatval($this->getSecretParam('money'));

        $need_check_config = Config::findOne(['config_name' => 'user_withdrawal_need_check']);
        $once_max_config = Config::findOne(['config_name' => 'user_withdrawal_once_max']);
        $day_max_config = Config::findOne(['config_name' => 'user_withdrawal_day_max']);
        $need_check = intval($need_check_config->config_value);
        $once_max_money = floatval($once_max_config->config_value);
        $day_max_money = floatval($day_max_config->config_value);

        $hasDones = Withdrawals::find()
                ->select(['ifnull(sum(amount),0) as amount'])
                ->where([
                    'user_id' => $user->id,
                    'status' => [Withdrawals::STATUS_APPLYING, Withdrawals::STATUS_CHECK_SUCCESS, Withdrawals::STATUS_PAY_SUCCESS]]
                )
                ->andWhere(['between', 'created_at', strtotime('today'), strtotime('today +1 day')])
                ->column();

        if ($day_max_money < $hasDones[0] + $money) {
            //超出每日最大提现金额
            return new Response(Response::CODE_USER_MONEY_WITHDRAWAL_FAILED, null, null, ['param' => '超出每日最大提现金额']);
        } else if ($once_max_money < $money) {
            //超出每单最大提现金额
            return new Response(Response::CODE_USER_MONEY_WITHDRAWAL_FAILED, null, null, ['param' => '超出每单最大提现金额']);
        }

        $model = new Withdrawals([
            'user_id' => $user->id,
            'order_sn' => Withdrawals::getRandomSN(),
            'amount' => $money,
            'need_check' => $need_check,
            'pay_account' => $user->auths->identifier,
            'pay_realname' => $user->nickname,
        ]);
        if (!$need_check) {
            $model->check_at = time();
            $model->check_feedback = '系统自动审核通过！10分钟内到账！';
            $model->status = Withdrawals::STATUS_CHECK_SUCCESS;
        }

        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $model);
        }else{
            return new Response(Response::CODE_USER_MONEY_WITHDRAWAL_FAILED, null, $model->getErrorSummary(true));
        }
    }

}
