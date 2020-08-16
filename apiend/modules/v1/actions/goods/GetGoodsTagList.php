<?php


namespace apiend\modules\v1\actions\goods;


use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\Goods;

/**
 * 获取绘本标签列表
 * Class GetGoodsTagList
 * @package apiend\modules\v1\actions\goods
 */
class GetGoodsTagList extends BaseAction
{
    public function run()
    {
        $category_id = $this->getSecretParam('category_id', '');
        $res = Goods::find()->andFilterWhere(['category_id' => $category_id])->select(['tags'])->column();
        $tags = array_filter(array_unique(implode(',', implode(',', $res))));
        return new Response(Response::CODE_COMMON_OK, NULL, $tags);
    }
}