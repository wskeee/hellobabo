<?php

namespace apiend\modules\v1\actions\order;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\order\Groupon;
use Yii;

/**
 * 结束团购邀请
 *
 * @author Administrator
 */
class EndGrouponInvite extends BaseAction
{

    protected $requiredParams = ['groupon_id'];

    public function run()
    {
        $user = \Yii::$app->user;
        $groupon_id = $this->getSecretParam('groupon_id');
        $groupon = Groupon::findOne(['id' => $groupon_id]);

        if ($groupon == null) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => Yii::t('app', 'Groupon')]);
        }

        if ($groupon->status == Groupon::STATUS_FINISHED && $groupon->created_by != $user->id) {
            return new Response(Response::CODE_COMMON_FORBIDDEN);
        }

        $groupon->status = Groupon::STATUS_FINISHED;
        if ($groupon->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $groupon);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, implode(',', $groupon->getErrorSummary(true)));
        }
    }

}
