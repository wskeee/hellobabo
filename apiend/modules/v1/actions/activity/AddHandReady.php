<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivity;
use common\models\activity\VoteActivityHand;
use Yii;

/**
 * 参赛准备
 *
 * @author Administrator
 */
class AddHandReady extends BaseAction
{
    protected $requiredParams = ['activity_id'];

    public function run()
    {
        $activity_id = $this->getSecretParam('activity_id');

        $activity = VoteActivity::findOne(['id' => $activity_id]);
        $model = VoteActivityHand::findOne(['activity_id' => $activity_id, 'target_user_id' => Yii::$app->user->id]);

        return new Response(Response::CODE_COMMON_OK, null, [
            'activity' => $activity,
            'activity_hand' => $model,
        ]);
    }

}
