<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\OrderRecommend;
use common\models\User;
use Yii;

/**
 * 获取收益列表
 *
 * @author Administrator
 */
class GetIncomeList extends BaseAction
{

    public function run()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $amount = OrderRecommend::find()->where(['recommend_by' => $user->id])->sum('amount');
        $results = OrderRecommend::find()
                ->where(['recommend_by' => $user->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(50)
                ->with('creater')
                ->all();

        /* @var $recommend OrderRecommend */
        $list = [];
        foreach ($results as $recommend) {
            $item = $recommend->toArray();
            $item['buy_user'] = $recommend->creater->nickname;
            $item['income'] = $recommend->amount;
            $item['time'] = date('Y-m-d H:i:s', $recommend->created_at);
            
            $list []= $item;
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'total_money' => $amount,
            'list' => $list,
        ]);
    }

}
