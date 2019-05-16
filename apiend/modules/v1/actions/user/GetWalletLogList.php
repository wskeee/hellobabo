<?php

namespace apiend\modules\v1\actions\user;

use apiend_sj\models\Response;
use apiend_sj\modules\v1\actions\BaseAction;
use common\models\platform\WalletLog;
use Yii;

/**
 * 获取订单列表
 */
class GetWalletLogList extends BaseAction {

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
        //类型
        $type = $this->getSecretParam('type', null);

        $query = WalletLog::find()->where(['user_id' => $user_id]);
        if (!empty($type)) {
            $query->andFilterWhere(['type' => $type]);
        }
        if ($date && $date != "") {
            $query->andFilterWhere(['between', 'created_at', strtotime($date), strtotime("$date +1 day")]);
        }

        //计算总数
        $totalQuery = clone $query;
        $totalQuery->select(['id']);

        $query->orderBy(['created_at' => SORT_DESC]);
        $query->offset(($page - 1) * $limit)->limit($limit);

        return new Response(Response::CODE_COMMON_OK, null, [
            'total' => $totalQuery->count()[0],
            'page' => $page,
            'list' => $query->all(),
        ]);
    }

}
