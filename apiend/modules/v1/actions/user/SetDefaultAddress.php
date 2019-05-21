<?php

namespace apiend\modules\v1\actions\user;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\UserAddress;
use Yii;
use yii\db\Expression;

/**
 * 设置默认地址
 * @author Administrator
 */
class SetDefaultAddress extends BaseAction
{
    /* 必须参数 */

    protected $requiredParams = ['id'];

    public function run()
    {
        $id = $this->getSecretParam('id');
        UserAddress::updateAll(['is_default' => new Expression("IF(id=$id,1,0)")], ['user_id' => Yii::$app->user->id]);
        $list = UserAddress::find()->where([
                    'user_id' => Yii::$app->user->id,
                    'is_del' => 0,
                ])->all();

        return new Response(Response::CODE_COMMON_OK, null, $list);
    }

}
