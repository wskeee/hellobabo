<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivityHand;
use Yii;

/**
 * 获取参赛数据
 *
 * @author Administrator
 */
class GetHand extends BaseAction
{

    public function run()
    {
        $activity_id = $this->getSecretParam('activity_id');
        $hand_id = $this->getSecretParam('id');

        if ($hand_id != null) {
            $model = VoteActivityHand::findOne(['id' => $hand_id]);
        } else if ($activity_id != null) {
            $model = VoteActivityHand::findOne(['activity_id' => $activity_id, 'target_user_id' => Yii::$app->user->id]);
        }

        return new Response(Response::CODE_COMMON_OK, null, $model);
    }

}
