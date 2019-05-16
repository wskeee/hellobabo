<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\platform\Withdrawals;
use Yii;

class GetWithdrawalList extends BaseAction {

    public function run() {
        if (!$this->verify()) {
            return $this->verifyError;
        }
        //ID
        $user_id = Yii::$app->user->id;
        //指定要获取的页
        $page = $this->getSecretParam('page', 1);
        //每页返回多少条数据
        $limit = $this->getSecretParam('page_size', 20);
        //指定时间
        $date = $this->getSecretParam('date');

        $query = Withdrawals::find()->where(['user_id' => $user_id]);
        
        if ($date && $date != "") {
            $query->andFilterWhere(['between', 'created_at', strtotime($date), strtotime("$date +1 day")]);
        }

        //计算总数
        $totalQuery = clone $query;
        $totalQuery->select(['id']);

        $query->select(['id','order_sn','amount','check_at','check_feedback','pay_at','pay_error_code','status','created_at']);
        $query->orderBy(['created_at' => SORT_DESC]);
        $query->offset(($page - 1) * $limit)->limit($limit);
        $query->asArray();
        
        $list = $query->all();
        
        foreach($list as &$model){
            $model['status_text'] = Withdrawals::$statusNameMap[$model['status']];
            $model['created_time'] = date('Y-m-d H:i:s',$model['created_at']);
            $model['check_time'] = date('Y-m-d H:i:s',$model['check_at']);
            $model['pay_time'] = date('Y-m-d H:i:s',$model['pay_at']);
            switch ($model['status']){
                case Withdrawals::STATUS_APPLYING:
                    $model['feedback'] = '您已经提交了申请，我们会尽快为您审核！';
                    break;
                case Withdrawals::STATUS_CHECK_FAILED:
                case Withdrawals::STATUS_APPLYING:
                   $model['feedback'] = $model['check_feedback'];
                    break;
                case Withdrawals::STATUS_PAY_FAILED:
                    $model['feedback'] = $model['pay_error_code'];
                    break;
                case Withdrawals::STATUS_PAY_SUCCESS:
                    $model['feedback'] = "{$model['pay_time']} 提现成功！";
                    break;
            }
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'total' => $totalQuery->count()[0],
            'page' => $page,
            'list' => $list,
        ]);
    }

}
