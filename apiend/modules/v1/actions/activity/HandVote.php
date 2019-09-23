<?php

namespace apiend\modules\v1\actions\activity;

use apiend\components\voterank\VoteService;
use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivityHand;

/**
 * 投票
 *
 * @author Administrator
 */
class HandVote extends BaseAction
{

    protected $requiredParams = ['hand_id'];

    public function run()
    {
        $hand_id = $this->getSecretParam('hand_id');
        $uid = \Yii::$app->user->id;

        $model = VoteActivityHand::findOne(['id' => $hand_id]);

        if (!$model) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '数据']);
        }

        // 当前用户今天投票总数，计算是否超出最大投票数
        $response = VoteService::vote($model->activity_id, $hand_id, $uid);
        
        return $response;
    }

}
