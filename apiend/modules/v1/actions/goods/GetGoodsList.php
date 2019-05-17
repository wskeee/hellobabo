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
        if (!$this->verify()) {
            return $this->verifyError;
        }
        /* @var $user User */
        $user = Yii::$app->user->identity;
        $list = Goods::find()
                        ->where([
                            'status' => Goods::STATUS_PUBLISHED,
                        ])->all();

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
