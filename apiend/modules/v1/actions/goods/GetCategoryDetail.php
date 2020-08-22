<?php


namespace apiend\modules\v1\actions\goods;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;
use common\models\goods\GoodsCategory;

class GetCategoryDetail extends BaseAction
{
    protected $requiredParams = ['category_id'];

    public function run()
    {
        $category_id = $this->getSecretParam('category_id');
        $category = GoodsCategory::findOne(['id' => $category_id])->toArray();
        $res = Goods::find()
            ->select(['tags'])
            ->where(['status' => Goods::STATUS_PUBLISHED])
            ->andFilterWhere(['category_id' => $category_id])
            ->column();
        $tags = array_filter(array_unique(explode(',', implode(',', $res))));
        $category['tags'] = $tags;
        return new Response(Response::CODE_COMMON_OK, null, $category);
    }
}