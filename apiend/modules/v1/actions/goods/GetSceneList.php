<?php

namespace apiend\modules\v1\actions\goods;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\goods\GoodsScene;
use common\models\goods\SceneGroup;
use yii\helpers\ArrayHelper;

/**
 * 获取场景列表
 *
 * @author Administrator
 */
class GetSceneList extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['goods_id'];
    
    public function run()
    {
        //场景列表
        $list = GoodsScene::find()
                        ->where([
                            'goods_id' => $this->getSecretParam('goods_id'),
                            'is_del' => 0,
                        ])->all();
        //场景分组
        $groups = SceneGroup::find()
                ->where(['id' => ArrayHelper::getColumn($groups, 'group_id'), 'is_del' => 0])
                ->all();

        return new Response(Response::CODE_COMMON_OK, null, [
            'scenes' => $list,
            'groups' => $groups,
        ]);
    }

}
