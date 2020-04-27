<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\order\Groupon;
use common\models\order\GrouponRecord;
use common\models\order\OrderGoods;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取我的绘本
 */
class GetMyOrderGoods extends BaseAction
{

    public function run()
    {
        $user = Yii::$app->user->identity;
        $orderGoods = OrderGoods::find()
                ->where(['created_by' => $user->id, 'is_del' => 0])
                ->andWhere(['<>', 'status', OrderGoods::STATUS_UNREADY])
                ->andWhere(['<>', 'status', OrderGoods::STATUS_INVALID])
                ->orderBy(['created_at' => SORT_DESC])
                ->with(['creater','comments','comments.creater'])
                ->all();
        /* 团购 */
        $groupon_ids = ArrayHelper::getColumn($orderGoods, 'groupon_id');
        $groupons = ArrayHelper::index(Groupon::find()->where(['id' => $groupon_ids])->andWhere(['<>', 'status', Groupon::STATUS_INVALID])->all(), 'id');

        /* 团购成员 */
        $groupon_records_result = GrouponRecord::getGrouponRecordDetail($groupon_ids);
        $groupon_records = ArrayHelper::index($groupon_records_result, null, 'groupon_id');
        $list = [];
        /* @var $goods OrderGoods */
        foreach ($orderGoods as $goods) {
            $item = $goods->toArray();
            $item['created_time'] = date('Y-m-d H:i:s', $goods->created_at);
            $item['commission'] = $goods->goods->commission;
            $item['goods_params'] = json_decode($item['goods_params']);
            $item['comments'] = empty($goods->comments) ? [] : $goods->comments;

            // 处理团购数据
            if ($goods->type == Goods::TYPE_GROUPON) {
                $groupon = isset($groupons[$goods->groupon_id]) ? $groupons[$goods->groupon_id] : null;
                $role_num = $item['goods_params']->role_num;
                if ($groupon != null) {
                    $item['groupon'] = $groupon;
                    $item['groupon_records'] = array_pad(isset($groupon_records[$groupon->id]) ? $groupon_records[$groupon->id] : [], $role_num, 0);
                } else {
                    $item['groupon_records'] = array_pad([], $role_num - 1, 0);
                }
            }

            $list [] = $item;
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'list' => $list,
        ]);
    }

}
