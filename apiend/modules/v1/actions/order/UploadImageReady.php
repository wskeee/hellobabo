<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\AdminUser;
use common\models\goods\GoodsPagePose;
use common\models\goods\GoodsScenePage;
use common\models\goods\ShootingAction;
use common\models\goods\ShootingAngle;
use common\models\goods\ShootingFace;
use common\models\order\OrderGoods;
use common\models\order\OrderGoodsScenePage;
use common\utils\I18NUitl;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 初始绘本准备
 * 返回绘本素材和场景
 */
class UploadImageReady extends BaseAction
{

    public function run()
    {
        $order_goods_id = $this->getSecretParam('order_goods_id');

        $orderGoods = OrderGoods::find()->where(['id' => $order_goods_id])->with('goods')->with('goods.category')->one();
        if (!$orderGoods) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => I18NUitl::t('app', '{Order}{Goods}')]);
        }
        $pages = (new Query())
                ->select([
                    'OrderGoodsScenePage.*',
                    'Pose.filepath pose_url',
                ])
                ->from(['OrderGoodsScenePage' => OrderGoodsScenePage::tableName()])
                ->leftJoin(['SourcePage' => GoodsScenePage::tableName()], 'OrderGoodsScenePage.page_id = SourcePage.id')
                ->leftJoin(['Pose' => GoodsPagePose::tableName()], 'SourcePage.id = Pose.page_id')
                ->where([
                    'OrderGoodsScenePage.order_goods_id' => $order_goods_id,
                    'OrderGoodsScenePage.is_required' => 1,
                    'OrderGoodsScenePage.is_del' => 0
                ])
                ->orderBy(['sort_order' => SORT_ASC])
                ->all();

        $source_page_ids = ArrayHelper::getColumn($pages, 'page_id');

        $actions = ArrayHelper::index(ShootingAction::find()->where(['page_id' => $source_page_ids, 'is_del' => 0])->all(), null, 'page_id');
        
        foreach($pages as &$page){
            $page['actions'] = isset($actions[$page['page_id']]) ? $actions[$page['page_id']] : [];
        }

        return new Response(Response::CODE_COMMON_OK, null, [
            'orderGoods' => $orderGoods,
            'goods' => $orderGoods->goods,
            'category' => $orderGoods->goods->category,
            'pages' => $pages,
            'actions' => $actions,
        ]);
    }

}
