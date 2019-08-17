<?php

namespace apiend\modules\v1\actions\element;

use apiend\models\Response;
use common\models\goods\GoodsElement;
use yii\base\Action;

/**
 * 获取元素列表
 *
 * @author Administrator
 */
class GetElementList extends Action
{
    public function run()
    {
        \Yii::$app->response->format = 'json';
        
        $elements = GoodsElement::find()->all();

        return new Response(Response::CODE_COMMON_OK, null, $elements);
    }

}
