<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsSpecPrice;
use common\models\User;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * 获取绘本/商品列表
 *
 * @author Administrator
 */
class GetGoodsList extends BaseAction
{

    public function run()
    {
        $category_id = $this->getSecretParam('category_id', '');
        $keyword = $this->getSecretParam('keyword', '');
        $page = $this->getSecretParam('page', 1);
        $page_size = $this->getSecretParam('page_size', 10);

        $query = Goods::find()
            ->where([
                'status' => Goods::STATUS_PUBLISHED,
            ])
            ->andFilterWhere([
                'category_id' => $category_id,
            ])
            ->andFilterWhere(['OR', ['like', 'goods_name', $keyword], ['like', 'tags', $keyword]]);
        // 总数
        $total_query = clone $query;
        $total = $total_query->count();

        $list = $query->offset(($page - 1) * $page_size)
            ->limit($page_size)
            ->orderBy(['is_top' => SORT_DESC, 'sort_order' => SORT_DESC])
            ->asArray()
            ->all();

        // 查询价格
        $priceMap = ArrayHelper::map(GoodsSpecPrice::find()
            ->select(['goods_id', new Expression('Min(goods_price) AS price')])
            ->where(['goods_id' => array_column($list, 'id'),'is_del' => 0])
            ->groupBy(['goods_id'])
            ->asArray()
            ->all(), 'goods_id', 'price');

        foreach ($list as &$goods) {
            $goods['price'] = isset($priceMap[$goods['id']]) ? $priceMap[$goods['id']] : 0;
        }

        $data = [
            'total' => (int)$total,
            'page' => (int)$page,
            'page_size' => (int)$page_size,
            'list' => $list,
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
