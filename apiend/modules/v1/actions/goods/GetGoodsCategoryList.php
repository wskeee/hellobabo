<?php


namespace apiend\modules\v1\actions\goods;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsCategory;

class GetGoodsCategoryList extends BaseAction
{
    public function run()
    {
        $list = GoodsCategory::find()->where(['status' => 1, 'is_del' => 0])->orderBy(['sort_order' => SORT_DESC])->all();
        return new Response(Response::CODE_COMMON_OK, null, $list);
    }
}