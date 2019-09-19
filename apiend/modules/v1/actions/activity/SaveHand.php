<?php

namespace apiend\modules\v1\actions\activity;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\activity\VoteActivityHand;
use Yii;

/**
 * 保存参赛数据
 *
 * @author Administrator
 */
class SaveHand extends BaseAction
{

    protected $requiredParams = ['activity_id', 'target_name', 'target_age', 'target_img'];

    public function run()
    {
        $activity_id = $this->getSecretParam('activity_id');
        $hand_id = $this->getSecretParam('id');

        if ($hand_id != null) {
            $model = VoteActivityHand::findOne(['id' => $hand_id]);
        } else {
            $model = new VoteActivityHand([
                'activity_id' => $activity_id,
                'target_user_id' => Yii::$app->user->id,
            ]);
        }

        if (!$model) {
            return new Response(Response::CODE_COMMON_NOT_FOUND, null, null, ['param' => '数据']);
        }

        if ($model->check_status == 2) {
            return new Response(Response::CODE_COMMON_FORBIDDEN);
        }

        $model->setAttributes([
            'target_name' => $this->getSecretParam('target_name'),
            'target_age' => $this->getSecretParam('target_age'),
            'target_img' => $this->getSecretParam('target_img'),
            'check_status' => 0,
        ]);

        if ($model->validate() && $model->save()) {
            return new Response(Response::CODE_COMMON_OK, null, $model);
        } else {
            return new Response(Response::CODE_COMMON_SAVE_DB_FAIL, null, $model->getErrorSummary(true));
        }
    }

}
