<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\components\redis\RedisService;
use common\models\goods\Goods;
use common\models\goods\GoodsMaterial;
use common\models\goods\GoodsMaterialValue;
use common\models\goods\GoodsScene;
use common\models\goods\GoodsScenePreview;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * 获取临时订单场景配置
 *
 * @author Administrator
 */
class TempOrderSceneReady extends BaseAction
{
    protected $requiredParams = ['goods_id'];

    public function run()
    {

        $goods_id = $this->getSecretParam('goods_id');
        $user_id = Yii::$app->user->id;
        /* @var User $user_model */
        $user_model = Yii::$app->user->identity;

        // 商品
        /* @var Goods $goods */
        $goods = Goods::find()->where(['id' => $goods_id])->one();
        if ($goods == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, ['param' => '绘本']);
        }

        // 临时订单
        $tempOrder = GetTempOrder::getTempOrder($user_id,$goods_id);
        if(empty($tempOrder)){
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, ['param' => '临时订单']);
        }

        // 素材和商品素材值
        $material_value_id = $tempOrder['material_value_id'];
        $previews = GoodsScene::getGoodsPreview($goods_id,$material_value_id);
        //$scenes = ArrayHelper::index(GoodsScene::find()->where(['id' => array_column($previews,'scene_id')])->all(),'id');
        $data = [
            'goods' => $goods,
            //'scenes' => $scenes,
            'previews' => $previews,
            'temp_order' => $tempOrder,
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

}
