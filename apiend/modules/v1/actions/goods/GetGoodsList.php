<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\User;
use Yii;

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

        $list = Goods::find()
            ->where([
                'status' => Goods::STATUS_PUBLISHED,
            ])
            ->andFilterWhere([
                'category_id' => $category_id,
            ])
            ->andFilterWhere(['OR', ['like', 'name', $keyword], ['like', 'tags', $keyword]])
            ->offset(($page - 1) * $page_size)
            ->limit($page_size)
            ->all();

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
